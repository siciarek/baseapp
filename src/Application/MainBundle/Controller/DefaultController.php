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
     * @Route("/json-test", name="default.json.test")
     */
    public function jsonAction() {
        $data = [];

        $auth = [
            'login' => 'siciarek',
            'password' => 'helloworld',
        ];

        $frame = $this->get('app.common.laaf.frame')->getDataFrame(null, $data, true, $auth);

        return $this->getJsonResponse($frame);
    }
    
    /**
     * @Route("/", name="default.home")
     * @Template()
     */
    public function homeAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("/info", name="default.info")
     * @Template()
     */
    public function infoAction(Request $request)
    {
        return [];
    }
    
    /**
     * @Route("/about", name="default.about")
     * @Template()
     */
    public function aboutAction(Request $request)
    {
        return [];
    }
    
    /**
     * @Route("/contact", name="default.contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        return [];
    }    
}
