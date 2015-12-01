<?php
namespace Application\DiscBundle\Service;

use Symfony\Component\Yaml\Yaml;

class ClassicalPattern {
    
    public function getNames() {
        $list = $this->getList();
        $names = [];

        foreach($list as $key => $group) {
            foreach($group as $g) {
                $names[$g] = true;
            }
        }

        $names = array_keys($names);
        
        sort($names);

        return $names;
    }
    
    public function getProfile($result) {
        $list = $this->getList();
        $group = substr(strval($result), 0, 2) . '77';
        
        return $list[$group][strval($result)];
    }
    
    public function getList() {

        $filename = realpath(__DIR__ . '/../../DiscBundle/Resources/config/patterns.yml');
        $patterns = Yaml::parse(file_get_contents($filename))['ClassicalPattern'];

        return $patterns;
    }
}