<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class CommonController extends Controller {

    /**
     * Zwraca dane JSON wysłane postem jako tablicę lub obiekt
     *
     * @param boolean $array czy zwracać jako tablicę
     * @return array|mixed
     */
    protected function getJsonRequest(Request $request, $array = true) {
        $input = $request->get('json');

        if ($input === null) {
            $input = file_get_contents('php://input');
        }

        $data = json_decode($input, $array);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $this->get('app.common.laaf.frame')->getErrorFrame();
            $data['msg'] = json_last_error_msg();
            $data['data'] = [];
            $data['data']['code'] = 500;
            $data['data']['input'] = $input;

            $data = json_decode(json_encode($data), $array);
        }

        return $data;
    }

    /**
     * Handles json response
     *
     * @param type $func callable
     */
    protected function handleJsonAction($func) {
        try {
            $frame = $func();
        } catch (\Exception $e) {

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
        $request = $this->get('request');
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        
        $contentType = 'application/json';
        $content = $json;

        // <jsonp>
        $callback = $request->get('callback');

        if ($callback !== null) {
            $contentType = 'application/javascript';
            $content = sprintf('%s(%s);', $callback, $json);
        }
        // </jsonp>
        
        $response = new Response($content, 200, [ 'Content-Type' => $contentType ]);

        return $response;
    }
}
