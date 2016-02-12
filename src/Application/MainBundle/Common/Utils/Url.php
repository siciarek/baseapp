<?php
namespace Application\MainBundle\Common\Utils;

class InvalidUrlException extends \Exception {
    public function __construct($message = 'Invalid url.', $code = 404,  \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class Url {

    /**
     * Data storage
     * @var array
     */
    protected $data = null;
    
    /**
     * Empty frame
     * @var array
     */
    protected static $frame = [
        'scheme' => null,
        'host' => null,
        'port' => null,
        'user' => null,
        'pass' => null,
        'path' => null,
        'query' => null,
        'fragment' => null,
        'tld' => null,
    ];

    /**
     * Parse url
     * 
     * @param string $url Given url
     * @param boolean $strict if set to true throw exception on empty url
     * @return boolean true if parsing succeed false otherwise
     * @throws \Application\MainBundle\Common\Utils\InvalidUrlException
     */
    public function parse($url = null, $strict = false) {

        $temp = parse_url($url);

        foreach ($temp as $key => $val) {
            $val = trim($val);
            if (empty($val)) {
                unset($temp[$key]);
            }
        }

        if($strict == true and count($temp) === 0) {
            throw new InvalidUrlException();
        }
        
        $this->data = array_merge(self::$frame, $temp);
       
        $q = $this->data['query'];
        $q = str_replace('&amp;', '&', $q);
       
        parse_str($q, $query);
        $this->data['query'] = $query;
        
        if(!empty($this->data['host'])) {
            $x = explode('.', $this->data['host']);
            if(count($x) > 1) {
                $tld = array_pop($x);
                $tld = trim($tld);
                if(strlen($tld) > 0) {
                    $this->data['tld'] = $tld;
                }
            }
            
        }
        
        return count($temp) > 0 ? $this : null;
    }

    /**
     * Returns entire parsed url data as array
     * 
     * @return array
     * @throws \Exception
     */
    public function getData() {
        
        if(!is_array($this->data)) {
            throw new \Exception('Use parse() method.');
        }

        return $this->data;
    }

    /**
     * Parsed url data element getter
     * 
     * @param type $name
     * @param type $arguments
     * @return type
     * @throws \Exception
     */
    public function __call($name, $arguments) {

        $key = preg_replace('/^get([A-Z][a-z]+)$/', '$1', $name);

        if (!($key !== $name and array_key_exists(strtolower($key), self::$frame))) {
            $msg = 'Call to undefined method ' . __CLASS__ . '::' . $name;
            trigger_error($msg, E_USER_ERROR);
        }

        if(!is_array($this->data)) {
            throw new \Exception('Use parse() method.');
        }

        return $this->data[strtolower($key)];
    }
}
