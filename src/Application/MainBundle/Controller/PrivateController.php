<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doctrine\ORM\Query;
use \Application\MainBundle\Common\Form\EmailMessageType;

/**
 * @Route("/private")
 */
class PrivateController extends Controller
{

    /**
     * @Route("/sample/form", name="private.sample.form")
     * @Template()
     */
    public function sampleFormAction(Request $request)
    {

        $form = $this->createForm(EmailMessageType::class, null, ['render_submit_button' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getNormData();

            $data['email'] = $this->get('fos_user.util.email_canonicalizer')
                    ->canonicalize($data['email']);

            $data['attachments'] = array_filter($data['attachments'], function($e) {
                return $e instanceof \Symfony\Component\HttpFoundation\File\UploadedFile;
            });

            $to = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            $from = [
                'name' => $this->container->getParameter('app_name'),
                'email' => $this->get('fos_user.util.email_canonicalizer')
                    ->canonicalize($this->container->getParameter('mailer_default_email')),
            ];

            $bodyPlainText = strip_tags($data['body']);
            $bodyPlainText = trim($bodyPlainText);

            /**
             * @var \AppKernel
             */
            $kernel = $this->get('kernel');
            $tmpDir = implode(DIRECTORY_SEPARATOR, [$kernel->getCacheDir(), 'swiftmailer', 'tmp']);

            if (false === is_dir($tmpDir)) {
                mkdir($tmpDir, 0777, true);
            }

            if (false === is_writable($tmpDir)) {
                throw new \Exception(sprintf('Directory %s is not writable.', $tmpDir));
            }

            $message = \Swift_Message::newInstance()
                    ->setSubject($data['subject'])
                    ->setFrom($from['email'], $from['name'])
                    ->setTo($to['email'], $to['name'])
                    ->setBody($data['body'], 'text/html')
                    ->addPart($bodyPlainText, 'text/plain')
            ;

            foreach ($data['attachments'] as $a) {
                $fname = tempnam($tmpDir, 'att');
                copy($a->getRealPath(), $fname);

                $attachment = \Swift_Attachment::fromPath($fname, $a->getMimeType())
                        ->setFilename($a->getClientOriginalName());

                $message->attach($attachment);
            }

            $result = $this->get('mailer')->send($message);

            if ($result !== 1) {
                throw new \Exception($result);
            }
            
            ldd($result);
        }

        return [
            'form' => $form,
        ];
    }

    /**
     * @Route("/sample/list", name="private.sample.list")
     * @Template()
     */
    public function sampleListAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ApplicationMainBundle:CollectionElement');
        $qb = $repo->createQueryBuilder('e')
                ->leftJoin('e.translations', 't')
                ->andWhere('t.locale = :locale')
                ->setParameter('locale', $request->getLocale());

        $query = $qb->getQuery();

        $page = $request->query->getInt('page', 1);
        $pageSize = $this->container->getParameter('pager.size');
        $options = [];
        $pagination = $this->get('knp_paginator')->paginate($query, $page, $pageSize, $options);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/gallery", name="private.gallery")
     * @Template()
     */
    public function galleryAction()
    {
        return [];
    }

    /**
     * @Route("/", name="private.index")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/spreadsheet", name="private.spreadsheet")
     */
    public function spreadsheetAction(Request $request)
    {

        $repository = $this->getDoctrine()
                ->getRepository('ApplicationMainBundle:CollectionElement');

        $query = $repository
                ->createQueryBuilder('e')
                ->select('e.id, e.enabled, e.type, t.name, t.info')
                ->leftJoin('e.translations', 't')
                ->andWhere('t.locale = :locale')->setParameter('locale', $request->getLocale())
                ->orderBy('t.name', 'ASC')
                ->getQuery();

        $title = 'Elements';
        $headers = ['id', 'enabled', 'type', 'name', 'info'];
        $data = $query->getResult(Query::HYDRATE_ARRAY);

        $fileName = mb_convert_case($title, MB_CASE_LOWER);

        return $this->returnXlsResponse($data, $headers, $title, $fileName);
    }

    protected function returnXlsResponse($data, $headers, $title = 'Data', $fileName = 'spreadsheet')
    {

        $srv = $this->get('phpexcel');

        // ask the service for a Excel5
        $phpExcelObject = $srv->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator($this->getUser()->getUsername())
                ->setLastModifiedBy($this->getUser()->getFullName())
//                ->setTitle("Office 2005 XLSX Test Document")
//                ->setSubject("Office 2005 XLSX Test Document")
//                ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
//                ->setKeywords("office 2005 openxml php")
//                ->setCategory("Category")
        ;


        $phpExcelObject->setActiveSheetIndex(0);

        $phpExcelObject->getActiveSheet()->fromArray($headers, null, 'A1');
        $phpExcelObject->getActiveSheet()->fromArray($data, null, 'A2');
        $phpExcelObject->getActiveSheet()->setTitle($title);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $srv->createWriter($phpExcelObject, 'Excel5');

        // create the response
        $response = $srv->createStreamedResponse($writer);

        $fileName .= '.xls';

        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

}
