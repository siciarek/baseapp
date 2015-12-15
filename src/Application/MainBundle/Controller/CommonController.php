<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class CommonController extends Controller {

    /**
     * Returns a LAAF frame response.
     */
    protected function getJsonResponse($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response = new Response($json, 200, [ 'Content-Type' => 'application/json']);

        return $response;
    }
}
