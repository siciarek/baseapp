<?php

namespace Application\DiscBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @Route("/disc")
 */
class DefaultController extends Controller
{
     /**
     * @Route("/classical-patterns", name="disc.classical.patterns")
     * @Template()
     */
    public function classicalPatternsAction() {
        return [
            'items' => $this->get('disc.classical.pattern')->getList(),
        ];   
    }
    
    protected function getSurvey() {
        $url = 'https://www.123test.com/disc-personality-test/';
        $html = file_get_contents($url);
        
        $crawler = new Crawler($html);
        $crawler = $crawler->filter('table.groep');
        
        $survey = [
            'mode' => 'strict', // ['strict', 'soft']
            'questions' => [],
        ];

        foreach ($crawler as $element) {
            // td class="term niets
            $q = [];
            foreach($element->getElementsByTagName('td') as $node) {
                $value = trim($node->nodeValue);
                if(!empty($value))
                $q[] = $value;
            }
            
            $questions = array_combine(['D', 'i', 'S', 'C'], $q);
            
            $survey['questions'][] = $questions;
        }
        
        return $survey;
    }
    
    /**
     * @Route("/evaluate", name="disc.survey.evaluate")
     * @Template()
     */
    public function evaluateAction(Request $request) {
        
        $json = $request->get('json');
        $data = json_decode($json, true);
        if($data === null) {
            $json = '{"1":{"most":"D","least":"C"},"2":{"most":null,"least":"S"},"3":{"most":null,"least":"i"},"4":{"most":"D","least":null},"5":{"most":"D","least":"C"},"6":{"most":"i","least":"C"},"7":{"most":"i","least":"C"},"8":{"most":"S","least":"C"},"9":{"most":"D","least":"S"},"10":{"most":"i","least":"D"},"11":{"most":"S","least":"D"},"12":{"most":"C","least":"i"},"13":{"most":"i","least":"S"},"14":{"most":"i","least":"C"},"15":{"most":"D","least":"S"},"16":{"most":"D","least":"C"},"17":{"most":"i","least":"C"},"18":{"most":"i","least":"C"},"19":{"most":"i","least":"C"},"20":{"most":"D","least":"C"},"21":{"most":"D","least":"C"},"22":{"most":"i","least":"C"},"23":{"most":"D","least":"S"},"24":{"most":"i","least":"C"},"25":{"most":"D","least":"S"},"26":{"most":"D","least":"S"},"27":{"most":"D","least":"S"},"28":{"most":"i","least":"S"}}';
        //    throw new \Exception('Invalid data.');
        }
        $data = json_decode($json, true);
        
        $tallyBox = [
            'most' => [
                'D' => 0,
                'i' => 0,
                'S' => 0,
                'C' => 0,
                'N' => 0,
            ],
            'least' => [
                'D' => 0,
                'i' => 0,
                'S' => 0,
                'C' => 0,
                'N' => 0,
            ],
            'difference' => [
                'D' => 0,
                'i' => 0,
                'S' => 0,
                'C' => 0,
                'N' => 0,
            ],
        ];
        
        foreach($data as $pos) {
            foreach($pos as $key => $val) {
                $val = $val === null ? 'N' : $val;
                $tallyBox[$key][$val]++;
            }
        }
        
        foreach(['D', 'i', 'S', 'C'] as $type) {
            $tallyBox['difference'][$type] = $tallyBox['most'][$type] - $tallyBox['least'][$type];
        }
        
        return [
            'data' => json_encode($tallyBox, JSON_PRETTY_PRINT),
        ];
    }
    
    /**
     * @Route("/{id}", requirements={"id" = "[1-9]\d*"}, defaults={"id" = "3451237"}, name="disc.index")
     * @Route("/{id}/survey", requirements={"id" = "[1-9]\d*"}, defaults={"id" = "3451237"}, name="disc.survey")
     * @Template()
     */
    public function surveyAction($id) {
        
        $survey = $this->getSurvey();
        
        return [
            'user' => $id,
            'mode' => $survey['mode'],
            'survey' => $survey['questions'],
        ];
    }
}
