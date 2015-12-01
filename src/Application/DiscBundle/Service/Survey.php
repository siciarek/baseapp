<?php
namespace Application\DiscBundle\Service;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelector;

class Survey {
    
    public function getSurvey($id = 1)
    {
        $fileName = realpath(__DIR__ . '/../Resources/config/surveys.yml');
        $data = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($fileName));

        $temp = array_filter($data['Survey'], function($e) use ($id) {
             return $e['id'] == $id;
        });
        $survey = array_pop($temp);

        return $survey;
    }
}