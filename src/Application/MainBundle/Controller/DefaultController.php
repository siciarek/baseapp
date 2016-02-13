<?php

namespace Application\MainBundle\Controller;

use Application\MainBundle\Controller\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends CommonController
{

    /**
     * @Route("/file-pdf", name="default.pdf.test")
     */
    public function pdfAction()
    {

        $html = <<<HTML
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />      
</head>
<body>
                
<h1>Zażółć gęślą jaźń</h1>    
<hr/>    
<p>Agent 007 powrócił jak zwykle w towarzystwie pięknych kobiet. Na londyńskiej premierze "Spectre" odtwórca głównej roli Daniel Craig pojawił się w towarzystwie obu partnerek z planu: Moniki Bellucci i Lei Seydoux. - W tym filmie jest może nieco więcej ekstrawagancji i takiego staroświeckiego splendoru - przekonywał reżyser Sam Mendes. A Craig podkreślał, że "każdy dzień na planie był wspaniały". Do kin w Polsce film trafi 6 listopada. (http://www.tvn24.pl)</p>

<div>
<img src="http://i.kinja-img.com/gawker-media/image/upload/wpkp4may9t8nsljlztfh.jpg"/>
</div>
</body>    
</html>
HTML;
        $srv = $this->get('knp_snappy.pdf');

//        $html = $this->renderView('MyBundle:Foo:bar.html.twig', array(
//            'some' => $vars
//        ));

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'filename="document.pdf"'
        ];

        return new Response($srv->getOutputFromHtml($html), 200, $headers);
    }

    /**
     * @Route("/json-test", name="default.json.test")
     */
    public function jsonAction()
    {
        $data = [];

        $auth = [
            'login' => 'siciarek',
            'password' => 'helloworld',
        ];

        $frame = $this->get('app.common.laaf.frame')->getDataFrame(null, $data, true, $auth);

        return $this->getJsonResponse($frame);
    }

    /**
     * @Route("/contact", name="default.contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        $file = $this->get('kernel')->getRootDir() . '/../src/Application/MainBundle/Resources/data/Jacek_Siciarek.vcf';
         
        $vcard = new \Application\MainBundle\Common\Utils\Vcard($file);
        $data = $vcard->getData();
        
        return [
            'data' => $data,
        ];
    }

    /**
     * @Route("/", name="default.home")
     * @Template()
     */
    public function homeAction(Request $request)
    {
        return [];
    }

}
