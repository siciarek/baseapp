<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\Query;

/**
 * @Route("/private")
 */
class PrivateController extends Controller {

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/sample/list", name="private.sample.list")
     * @Template()
     */
    public function sampleListAction(Request $request) {

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
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     * @Route("/gallery", name="private.gallery")
     * @Template()
     */
    public function galleryAction() {
        return [];
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/", name="private.index")
     * @Template()
     */
    public function indexAction() {
        return [];
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/spreadsheet", name="private.spreadsheet")
     */
    public function spreadsheetAction(Request $request) {

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

    protected function returnXlsResponse($data, $headers, $title = 'Data', $fileName = 'spreadsheet') {

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
