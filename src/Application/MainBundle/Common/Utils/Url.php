<?php
namespace Application\MainBundle\Common\Utils;

class Url {

    protected $data;
    protected static $frame = [
        'scheme' => null,
        'host' => null,
        'port' => null,
        'user' => null,
        'pass' => null,
        'path' => null,
        'query' => null,
        'fragment' => null,
    ];

    public function __construct($url = null, $strict = false) {

        $temp = parse_url($url);

        foreach ($temp as $key => $val) {
            $val = trim($val);
            if (empty($val)) {
                unset($temp[$key]);
            }
        }

        if($strict == true and count($temp) === 0) {
            $msg = 'Invalid url.';
            throw new \Exception($msg);
        }
       
        $this->data = array_merge(self::$frame, $temp);
       
        $q = $this->data['query'];
        $q = str_replace('&amp;', '&', $q);
       
        parse_str($q, $query);
        $this->data['query'] = $query;
    }

    public function getData() {
        return $this->data;
    }

    public function __call($name, $arguments) {

        $key = preg_replace('/^get([A-Z][a-z]+)$/', '$1', $name);

        if (!($key !== $name and array_key_exists(strtolower($key), $this->data))) {
            $msg = 'Call to undefined method ' . __CLASS__ . '::' . $name;
            trigger_error($msg, E_USER_ERROR);
        }

        return $this->data[strtolower($key)];
    }

}
