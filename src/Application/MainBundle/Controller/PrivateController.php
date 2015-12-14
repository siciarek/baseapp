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
     * @Route("/results", name="disc.spreadsheet")
     */
    public function resultsAction() {

        $sample = <<<SAMPLE
-80	-20	80	60	40	20	10	-40
70	65	20	-40	30	50	30	-90
60	-10	70	10	40	20	40	-60
10	-10	10	-10	50	20	50	70
-80	-90	-20	-10	10	10	10	15
SAMPLE;

        $asample = explode("\n", $sample);

        $asample = array_map(function($e) {
            $temp = explode("\t", $e);
            $temp = array_map('intval', $temp);
            return $temp;
        }, $asample);

        $data = [];

        foreach (range(1, 15) as $i) {
            $url = 'http://ankieta.com?id=' . substr(md5($i), 0, 16);

            $values = [null, null, null, null, null, null, null, null,];

            if ($i <= count($asample)) {
                $values = $asample[$i - 1];
            }

            $row = [$i, 'Osoba ' . $i];
            $row = array_merge($row, $values);
            $row[] = $url;

            $data[] = $row;
        }


        $headers = ['Lp', 'Imię i nazwisko', 'Tak chciałbym się zachowywać', null, null, null, 'Tego ode mnie wymagają', null, null, null, 'Link do ankiety'];
        $subheaders = ['D', 'I', 'S', 'C'];
        $title = 'Data';
        $fileName = 'results';

        $style = [
            'general' => [
                'font' => [
                    'size' => 10,
                    'name' => 'Arial',
                ],
                'borders' => [
                    'allborders' => [
                        'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
            ],
            'header' => [
                'font' => [
                    'bold' => true,
                    'color' => [ 'rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => [ 'rgb' => '4472C4'],
                ],
                'alignment' => [
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'most' => [
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => [ 'rgb' => '6FA8DC'],
                ],
            ],
            'least' => [
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => [ 'rgb' => 'EA9999'],
                ],
            ],
        ];

        $version = 'Excel2007';
        $srv = $this->get('phpexcel');
        $phpExcelObject = $srv->createPHPExcelObject();
        $phpExcelObject->setActiveSheetIndex(0);
        $sheet = $phpExcelObject->getActiveSheet();
        $sheet->setTitle($title);

        $sheet->fromArray($headers, null, 'A1');

        $sheet->fromArray($subheaders, null, 'C2');
        $sheet->fromArray($subheaders, null, 'G2');

        $lastRow = count($data) + 2;

        $sheet->getStyle('A1:K' . $lastRow)->applyFromArray($style['general']);
        $sheet->getStyle('A1:K2')->applyFromArray($style['header']);
        $sheet->getStyle('C1:F2')->applyFromArray($style['most']);
        $sheet->getStyle('G1:J2')->applyFromArray($style['least']);

        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:F1');
        $sheet->mergeCells('G1:J1');
        $sheet->mergeCells('K1:K2');

        $sheet->getStyle('C3:J' . $lastRow)
                ->getNumberFormat()
                ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

        $sheet->fromArray($data, null, 'A3', true);
        $sheet->fromArray(['               '], null, 'B' . (count($data) + 4));
        foreach (['A', 'B', 'K'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->setSelectedCell('A1');


        // Chart:
        
        // http://www.sitepoint.com/generate-excel-files-charts-phpexcel/
            
        $dsl = array(
            new \PHPExcel_Chart_DataSeriesValues('String', 'Data!$D$1', NULL, 1),
            new \PHPExcel_Chart_DataSeriesValues('String', 'Data!$E$1', NULL, 1),
        );

        $xal = array(
            new \PHPExcel_Chart_DataSeriesValues('String', 'Data!$B$3:$B$17', NULL, 90),
        );

        $dsv = array(
            new \PHPExcel_Chart_DataSeriesValues('Number', 'Data!$C$3:$F$' . $lastRow, NULL, 90),
            new \PHPExcel_Chart_DataSeriesValues('Number', 'Data!$G$3:$J$' . $lastRow, NULL, 90),
        );

        $ds = new \PHPExcel_Chart_DataSeries(
                \PHPExcel_Chart_DataSeries::TYPE_LINECHART, \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, range(0, count($dsv) - 1), $dsl, $xal, $dsv
        );

        $pa = new \PHPExcel_Chart_PlotArea(NULL, array($ds));

        $legend = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

        $title = new \PHPExcel_Chart_Title('DISC');
        
        $chart = new \PHPExcel_Chart(
                'chart1', $title, $legend, $pa, true, 0, NULL, NULL
        );

        $chart->setTopLeftPosition('A19');
        $chart->setBottomRightPosition('J45');
        $sheet->addChart($chart);


        $writer = $srv->createWriter($phpExcelObject, $version);
        $response = $srv->createStreamedResponse($writer);

        $fileName .= $version === 'Excel2007' ? '.xlsx' : '.xls     ';
        $dispositionHeader = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
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
