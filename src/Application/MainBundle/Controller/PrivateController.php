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

/**
 * @Route("/private")
 */
class PrivateController extends Controller
{
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
    public function spreadsheetAction()
    {
        $title = 'The Beatles';
        $headers = ['Id', 'First name', 'Last name' ];
        $data = [ $headers ];
        $data[] = [1, 'John', 'Lennon'];
        $data[] = [2, 'Paul', 'McCartney'];
        $data[] = [3, 'George', 'Harrison'];
        $data[] = [4, 'Ringo', 'Star'];
        
        $fileName = 'spreadsheet.xls';
        
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        
        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");

        $phpExcelObject->setActiveSheetIndex(0)->fromArray($data, null, 'A1');            
        $phpExcelObject->getActiveSheet()->setTitle($title);
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
        
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );
        
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        
        return $response;        
    }
}