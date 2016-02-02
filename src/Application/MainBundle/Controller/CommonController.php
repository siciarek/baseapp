<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class CommonController extends Controller
{
    const MESSAGE_ERROR = 'error';
    const MESSAGE_WARNING = 'warning';
    const MESSAGE_INFO = 'info';
    const MESSAGE_SUCCESS = 'success';

    public static $customExceptions = [
        
    ];

    /**
     * Zwraca dane JSON wysłane postem jako tablicę lub obiekt
     *
     * @param boolean $array czy zwracać jako tablicę
     * @return array|mixed
     */
    protected function getJsonRequest($array = true)
    {

        $request = $this->get('request');
        $input = $request->get('json');

        if ($input === null) {
            $input = file_get_contents('php://input');
        }

        $data = json_decode($input, $array);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $this->getFrame()->getErrorFrame();
            $data['msg'] = json_last_error_msg();
            $data['data'] = [];
            $data['data']['code'] = 500;
            $data['data']['input'] = $input;

            $data = json_decode(json_encode($data), $array);
        }

        return $data;
    }

    /**
     * Returns json response.
     */
    protected function getJsonResponse($data)
    {
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

        $response = new Response($content, 200, [ 'Content-Type' => $contentType]);

        return $response;
    }

    /**
     * Handles json action
     *
     * @param type $run callable
     */
    protected function handleJsonAction($run)
    {
        try {
            $frame = $run();
        } catch (\Exception $e) {

            $frame = $this->getFrame()->getErrorFrame('Unexpected Exception.');

            if (in_array(get_class($e), self::$customExceptions)) {
                $frame = $this->getFrame()->getWarningFrame($e->getMessage());
            }

            $frame['data'] = [
                'code' => $e->getCode(),
            ];

            if ($this->get('kernel')->getEnvironment() != 'prod') {
                $frame['msg'] = $e->getMessage();
                $frame['data'] = array_merge($frame['data'], [
                    'class' => get_class($e),
                    'trace' => $e->getTrace(),
                ]);
            }
        }

        return $this->getJsonResponse($frame);
    }

    /**
     * Handles html action
     *
     * @param type $run callable
     */
    protected function handleHtmlAction($run, Request $request)
    {
        $url = null;

        try {
            $url = $run();
        } catch (\Exception $e) {

            $msg = 'Unexpected Exception.';
            $type = self::MESSAGE_ERROR;

            if (in_array(get_class($e), self::$customExceptions)) {
                $type = self::MESSAGE_WARNING;
                $msg = $e->getMessage();
            } elseif ($this->get('kernel')->getEnvironment() !== 'prod') {
                $msg = $e->getMessage();
            }

            $this->addFlash($type, $msg);
        }
        $url = empty($url) ? $request->headers->get('referer') : $url;        
        $url = empty($url) ? $request->getSchemeAndHttpHost() : $url;

        return $this->redirect($url);
    }

    protected function getFrame()
    {
        return $this->get('app.common.laaf.frame');
    }

}
