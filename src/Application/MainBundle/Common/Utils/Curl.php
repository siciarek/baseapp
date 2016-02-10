<?php

/**
 * Created by PhpStorm.
 * User: Jacek
 * Date: 09.10.14
 * Time: 11:40.
 */

namespace Application\MainBundle\Common\Utils;

/**
 * Class Curl
 * @package Application\Common\Utils
 *
 * Uwaga:
 *
 * metody GET(),POST(),PUT() i DELETE() zwracają hasza:
 *
 * [
 *      "headers" => []    // tablica zawierająca nagłówki odpowiedzi
 *      "info" => []       // tablica zawierająca informacje curl np. czas wykonywania, dane przekierowania itp.
 *      "response" => null // Zwracana odpowiedź w postaci stringa
 * ]
 */
class Curl {

    protected $opts = array();
    protected $default_headers = array();
    protected $auth = CURLAUTH_ANY;
    protected $headers = array();

    public function __construct($tempdir = null, $name = 'COOKIES', $debug = false) {
        $tempdir = $tempdir ? : sys_get_temp_dir();

        if (!is_dir($tempdir)) {
            $umask = umask(0000);
            mkdir($tempdir, 0777, true);
            umask($umask);
        }

        $cookies = $tempdir . DIRECTORY_SEPARATOR . $name;

        if (file_exists($cookies)) {
            unlink($cookies);
        }

        $this->default_headers = array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: pl,en-us;q=0.7,en;q=0.3',
            'Accept-Charset: ISO-8859-2,utf-8;q=0.7,*;q=0.7',
            'Keep-Alive: 115',
            'Connection: keep-alive',
        );

        $this->opts = array(
            CURLOPT_HTTPHEADER => $this->default_headers,
            CURLOPT_COOKIEFILE => $cookies,
            CURLOPT_COOKIEJAR => $cookies,
            CURLOPT_COOKIESESSION => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => $debug,
            CURLOPT_VERBOSE => $debug,
            CURLOPT_HEADERFUNCTION => array($this, 'headersHandler'),
        );
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function GET($url, $data = null, $username = null, $password = null) {
        $data = is_array($data) ? http_build_query($data) : $data;

        if (!empty($data)) {
            if (preg_match('/\?$/', $url)) {
                
            } else {
                $url .= preg_match('/\?/', $url) ? '&' : '?';
            }

            $url .= $data;
        }

        $opts = $this->opts;

        $opts[CURLOPT_HTTPGET] = true;
        $opts[CURLOPT_URL] = $url;

        if ($username != null and $password != null) {
            $opts[CURLOPT_HTTPAUTH] = $this->auth;
            $opts[CURLOPT_USERPWD] = sprintf('%s:%s', $username, $password);
        }

        return $this->response($opts);
    }

    public function POST($url, $data = null, $username = null, $password = null) {
        $data = is_array($data) ? http_build_query($data) : $data;

        $opts = $this->opts;

        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POSTFIELDS] = $data;

        if ($username != null and $password != null) {
            $opts[CURLOPT_HTTPAUTH] = $this->auth;
            $opts[CURLOPT_USERPWD] = sprintf('%s:%s', $username, $password);
        }

        return $this->response($opts);
    }

    public function PUT($url, $data = null, $username = null, $password = null) {
        $opts = $this->opts;
        $fp = fopen('php://temp', 'w');
        if ($data !== null) {
            fwrite($fp, $data);
        }
        fseek($fp, 0);

        $opts[CURLOPT_PUT] = true;
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_INFILE] = $fp;
        $opts[CURLOPT_INFILESIZE] = strlen($data);

        if ($username != null and $password != null) {
            $opts[CURLOPT_HTTPAUTH] = $this->auth;
            $opts[CURLOPT_USERPWD] = sprintf('%s:%s', $username, $password);
        }

        return $this->response($opts);
    }

    public function DELETE($url, $data = null, $username = null, $password = null) {
        $data = is_array($data) ? http_build_query($data) : $data;

        $opts = $this->opts;

        $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POSTFIELDS] = $data;

        if ($username != null and $password != null) {
            $opts[CURLOPT_HTTPAUTH] = $this->auth;
            $opts[CURLOPT_USERPWD] = sprintf('%s:%s', $username, $password);
        }

        return $this->response($opts);
    }

    public function setAuth($auth) {
        $this->auth = $auth;
    }

    protected function response(array $opts)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $response = curl_exec($ch);

        $info     = curl_getinfo($ch);
        $headers = $this->getHeaders();

        if($response === false) {
            throw new \Exception(curl_error($ch));
        }

        curl_close($ch);
        return array(
            'response' => trim($response),
            'info'     => $info,
            'headers'  => $headers,
        );
    }
    
    public function adjustHeaders($headers = array(), $merge = true) {
        if ($headers === array()) {
            $this->opts[CURLOPT_HTTPHEADER] = $this->default_headers;

            return;
        }

        $new_headers = $merge ? array_merge($headers, $this->default_headers) : $headers;

        $this->opts[CURLOPT_HTTPHEADER] = $new_headers;
    }

    public function adjustCookies($cookie) {
        $this->opts[CURLOPT_COOKIE] = $cookie;
    }

    protected function headersHandler($ch, $header) {
        $match = [];

        if (preg_match('/^([^:]+):\s*(.*?)$/', $header, $match)) {
            $label = trim($match[1]);
            $value = trim($match[2]);

            if (isset($this->headers[$label])) {
                $this->headers[$label] = array_merge((array) $this->headers[$label], (array) $value);
            } else {
                $this->headers[$label] = $value;
            }
        }

        return strlen($header);
    }

}
