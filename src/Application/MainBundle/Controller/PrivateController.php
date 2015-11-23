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

        $title = 'Elements';
        $category = 'Collections';

        $repository = $this->getDoctrine()
                ->getRepository('ApplicationMainBundle:CollectionElement');

        $query = $repository
                ->createQueryBuilder('e')
                ->select('e.id, e.enabled, e.type, t.name, t.info')
                ->leftJoin('e.translations', 't')
                ->andWhere('t.locale = :locale')->setParameter('locale', $request->getLocale())
                ->getQuery();

        $headers = ['id', 'enabled', 'type', 'name', 'info'];
        $data = $query->getResult(Query::HYDRATE_ARRAY);
        
        return $this->returnXlsResponse($data, $headers, $category, $title);
    }

    protected function returnXlsResponse($data, $headers, $category, $title, $fileName = 'spreadsheet.xls') {
         
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator($this->getUser()->getUsername())
                ->setLastModifiedBy($this->getUser()->getFullName())
                ->setTitle("Office 2005 XLSX Test Document")
                ->setSubject("Office 2005 XLSX Test Document")
                ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
                ->setKeywords("office 2005 openxml php")
                ->setCategory($category);

        
        $phpExcelObject->setActiveSheetIndex(0)->fromArray($headers, null, 'A1');
        $phpExcelObject->setActiveSheetIndex(0)->fromArray($data, null, 'A2');
        $phpExcelObject->getActiveSheet()->setTitle($title);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');

        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName
        );

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

}
