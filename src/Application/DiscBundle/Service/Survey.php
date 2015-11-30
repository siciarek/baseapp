<?php
namespace Application\DiscBundle\Service;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelector;

class Survey {
    
    public function getSurvey($id = 1)
    {
        $survey = [
            'mode'      => 'strict', // ['strict', 'soft']
            'questions' => [ ],
        ];

        if($id === 2) {
        
            $url  = 'https://www.123test.com/disc-personality-test/';
            $html = file_get_contents($url);
    
            $crawler = new Crawler($html);
            $crawler = $crawler->filter('table.groep');
    
            foreach ($crawler as $element) {
                // td class="term niets"
                $q = [ ];
                foreach ($element->getElementsByTagName('td') as $node) {
                    $value = trim($node->nodeValue);
                    if (!empty($value))
                        $q[] = $value;
                }
    
                $questions = array_combine([ 'D', 'i', 'S', 'C' ], $q);
    
                $survey['questions'][] = $questions;
            }
        }
        else {
            $fileName = realpath(__DIR__ . '/../Resources/config/surveys.yml');
            $data = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($fileName));
            $elements = $data['surveys']['default'];
            
            foreach ($elements as $element => $q) {
                
                $questions = array_combine([ 'D', 'i', 'S', 'C' ], $q);
    
                $survey['questions'][] = $questions;
            }
        }

        return $survey;
    }
}