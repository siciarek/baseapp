<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
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
