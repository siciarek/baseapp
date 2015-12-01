<?php
namespace Application\DiscBundle\Service;

use Symfony\Component\Yaml\Yaml;

class Survey {
    
    public function getSurvey($id = 1)
    {
        // TODO: pomyśleć o bazie danych
        
        $fileName = realpath(__DIR__ . '/../Resources/config/surveys.yml');
        $data = Yaml::parse(file_get_contents($fileName));

        $temp = array_filter($data['Survey'], function($e) use ($id) {
             return $e['id'] == $id;
        });
        $survey = array_pop($temp);

        return $survey;
    }
}