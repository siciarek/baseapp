<?php

namespace Application\DiscBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelector;

/**
 * @Route("/disc")
 */
class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="disc.index")
     * @Template()
     */
    public function indexAction()
    {
        return [
            
        ];
    }
    
    /**
     * @Route("/graph/{results}/{type}", requirements={"type"="most|least|difference", "results","[\d,]{7,11}"}, defaults={"type"="most", "results"="0,0,0,0"}, name="disc.graph")
     * @Template()
     */
    public function graphAction($type, $results)
    {
        $types = [
            'most' => 'I',
            'least' => 'II',
            'difference' => 'III',
        ];

        $type = $types[$type];

        $temp = explode(',', $results);
        $in   = array_combine([ 'D', 'i', 'S', 'C' ], $temp);

        $filename = realpath(sprintf('%s/../Resources/data/graph-%s.svg', __DIR__, $type));
        $xml      = file_get_contents($filename);

        CssSelector::disableHtmlExtension();
        $crawler = new Crawler($xml);
        $nodes   = $crawler->filter('default|svg default|g.label default|text.disc');

        $x = [ ];
        $final = [ ];
        $segments = [ ];

        foreach ($nodes as $node) {
            $x[$node->nodeValue] = $node->getAttribute('x');
        }

        foreach ($in as $dim => $value) {

            $crawler  = new Crawler($xml);
            $nodes    = $crawler->filter('default|svg default|text.indicator.' . $dim);
            $final[$dim] = [];

            foreach ($nodes as $node) {
                if ($value == $node->nodeValue) {
                    $segment                        = $node->getAttribute('class');
                    $segment                        = preg_replace('/.*segment-(\d).*/', '$1', $segment);
                    $segments[$dim]                = $segment;
                    $final[$dim][$node->nodeValue] = [ $x[$dim], $node->getAttribute('y') - 10, $segment ];
                }
            }
            
            if($final[$dim] == []) {
                $first = null;
                $last = null;
                $offset = 0;
                
                foreach ($nodes as $node) {
                    if($first === null) {
                        $first = $node;
                    }
                    $last = $node;
                }
                
                if($type === 'I') {
                    $node = $first;
                    $offset = 16;
                }
                elseif($type === 'II') {
                    $node = $last;
                    $offset = -16;
                }
                elseif($type === 'III') {
                    
                }
                
                $segment                        = $node->getAttribute('class');
                $segment                        = preg_replace('/.*segment-(\d).*/', '$1', $segment);
                $segments[$dim]                 = $segment;
                $final[$dim][$node->nodeValue] = [ $x[$dim], strval(($node->getAttribute('y') - 10) + $offset), $segment ];
            }
        }

        $contentFmt = <<<SVG
<?xml version="1.0" standalone="no"?>
<svg height="800" viewBox="0 0 687 1123" version="1.1" xmlns="http://www.w3.org/2000/svg"
     xmlns:xlink="http://www.w3.org/1999/xlink">
%s
</svg>
SVG;

        $segmentNumbers = [ 'D' => null, 'i' => null, 'S' => null, 'C' => null ];

        foreach ($segments as $dim => $s) {
            $nodes = $crawler->filter('default|svg default|g.result default|text.sn.dim-' . $dim);
            foreach ($nodes as $node) {
                $node->nodeValue  = $s;
                $segmentNumbers[$dim] = $s;
            }
        }

        $number = implode('', $segmentNumbers);
        
        // ldd($segmentNumbers, $number);

        $pattern = $this->get('disc.classical.pattern')->getProfile($number);


        $nodes = $crawler->filter('default|svg default|g.result default|text.cp');
        foreach ($nodes as $node) {
            $node->nodeValue = $pattern;
        }

        $xml = sprintf($contentFmt, $crawler->html());

        $chart = '';

        $temp = [ ];

        foreach ($final as $dim => $data) {
            foreach ($data as $v => $dat) {
                $temp[] = $dat[0] . ' ' . $dat[1];
                $chart .= sprintf('<circle cx="%d" cy="%d" r="16" stroke="none" fill="black" />' . "\n", $dat[0], $dat[1]);
            }
        }

        $d = 'M' . implode('L', $temp);

        $chart .= sprintf('<path class="chart-line" d="%s"/>', $d);

        $content = preg_replace('|</svg>|', $chart . '</svg>', $xml);

        $response = new Response($content);

        $response->headers->add([ 'Content-Type' => 'image/svg+xml' ]);

        return $response;
    }

    /**
     * @Route("/classical-patterns", name="disc.classical.patterns")
     * @Template()
     */
    public function classicalPatternsAction()
    {
        return [
            'items' => $this->get('disc.classical.pattern')->getList(),
        ];
    }

    /**
     * @Route("/evaluate", name="disc.survey.evaluate")
     * @Template()
     */
    public function evaluateAction(Request $request)
    {

        $json = $request->get('json');
        $data = json_decode($json, true);
        if ($data === null) {
            $json = '{"1":{"most":"D","least":"C"},"2":{"most":null,"least":"S"},"3":{"most":null,"least":"i"},"4":{"most":"D","least":null},"5":{"most":"D","least":"C"},"6":{"most":"i","least":"C"},"7":{"most":"i","least":"C"},"8":{"most":"S","least":"C"},"9":{"most":"D","least":"S"},"10":{"most":"i","least":"D"},"11":{"most":"S","least":"D"},"12":{"most":"C","least":"i"},"13":{"most":"i","least":"S"},"14":{"most":"i","least":"C"},"15":{"most":"D","least":"S"},"16":{"most":"D","least":"C"},"17":{"most":"i","least":"C"},"18":{"most":"i","least":"C"},"19":{"most":"i","least":"C"},"20":{"most":"D","least":"C"},"21":{"most":"D","least":"C"},"22":{"most":"i","least":"C"},"23":{"most":"D","least":"S"},"24":{"most":"i","least":"C"},"25":{"most":"D","least":"S"},"26":{"most":"D","least":"S"},"27":{"most":"D","least":"S"},"28":{"most":"i","least":"S"}}';
            //    throw new \Exception('Invalid data.');
        }
        $data = json_decode($json, true);

        $tallyBox = [
            'most'       => [
                'D' => 0,
                'i' => 0,
                'S' => 0,
                'C' => 0,
                'N' => 0,
            ],
            'least'      => [
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

        foreach ($data as $pos) {
            foreach ($pos as $key => $val) {
                $val = $val === null ? 'N' : $val;
                $tallyBox[$key][$val]++;
            }
        }

        foreach ([ 'D', 'i', 'S', 'C' ] as $type) {
            $tallyBox['difference'][$type] = $tallyBox['most'][$type] - $tallyBox['least'][$type];
        }

        return [
            'tallyBox' => $tallyBox,
            'data'     => json_encode($tallyBox, JSON_PRETTY_PRINT),
        ];
    }

    /**
     * @Route("/survey", requirements={"id" = "[1-9]\d*"}, defaults={"id" = "3451237"}, name="disc.survey.demo")
     * @Route("/{id}/survey", requirements={"id" = "[1-9]\d*"}, defaults={"id" = "3451237"}, name="disc.survey")
     * @Template()
     */
    public function surveyAction(Request $request)
    {
        $id = $request->get('id');
        $s = intval($request->get('s', 1));

        $survey = $this->get('disc.survey')->getSurvey($s);

        return [
            'user'   => $id,
            'mode'   => $survey['mode'],
            'survey' => $survey['questions'],
        ];
    }
}
