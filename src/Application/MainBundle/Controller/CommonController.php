<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class CommonController extends Controller {

    /**
     * Handles json response
     * 
     * @param type $func callable
     */
    protected function handleJsonAction($func) {
        try {
            $frame = $func();
        }  catch (\Exception $e) {
            
            $frame = $this->get('app.common.laaf.frame')
                    ->getErrorFrame('Unexpected Exception.');
            
            if ($this->get('kernel')->getEnvironment() != 'prod') {
               $frame['msg'] = $e->getMessage();
               $frame['data'] = $e->getTrace();
            }
        }
        
        return $this->getJsonResponse($frame);
    }
    
    
    /**
     * Returns json response.
     */
    protected function getJsonResponse($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response = new Response($json, 200, [ 'Content-Type' => 'application/json']);

        return $response;
    }

}
