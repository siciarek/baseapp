<?php

namespace Application\DiscBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassicalPatternTest extends WebTestCase
{
    protected $srv;
    
    /**

    Achiever
    Agent
    Appraiser
    Counselor
    Creative
    Developer
    Inspirational
    Investigator
    Objective Thinker
    Overshift
    Perfectionist
    Persuader
    Practitioner
    Promoter
    Result-Oriented
    Specialist
    Tight
    Undershift

    */

    public static function getProfileDataProvider() {
        return [
            [ 1467, 'Perfectionist'   ], // https://www.onlinediscprofile.com/wp-content/uploads/disc-classic-graph.png
            [ 4166, 'Perfectionist'   ], // http://www.intesiresources.com/images/DiSC-Classic-Graph.jpg
            [ 7711, 'Inspirational'   ], // http://discassessmentprofiles.com/wp-content/uploads/2014/02/figures-large.png
            [ 3622, 'Promoter'        ], // http://jcortright.typepad.com/.a/6a00d8341e2d7853ef0147e2f79cc2970b-pi
            [ 2657, 'Practitioner'    ], // http://www.discprofiles4u.com/product_images/uploaded_images/practitioner2.jpg
            [ 2674, 'Agent'           ], // http://www.discprofiles4u.com/blog/wp-content/uploads/2012/03/agent.jpg
            [ 6212, 'Developer'       ], // https://www.discprofiles4u.com/product_images/uploaded_images/disc-developer-pattern.jpg
            [ 5263, 'Achiever'        ], // http://kpaydo.aupairnews.com/files/2015/03/DiSC-model1.jpg
            [ 7513, 'Result-Oriented' ], // http://image.slidesharecdn.com/10148efa-ceb9-4af2-b41b-af3e76c78920-150116130553-conversion-gate01/95/zackyoung-21-638.jpg?cb=1421435201
            [ 7711, 'Inspirational'   ], // http://discassessmentprofiles.com/wp-content/uploads/2014/02/figures-large.png
        ];
    }
    
    /**
     * @group list
     */
    public function testGetList()
    {
        $list = $this->srv->getList();
        $this->assertEquals(49, count(array_keys($list)));
        
        foreach($list as $key => $values) {
            $this->assertEquals(49, count(array_keys($values)));
        }
    }
    
    /**
     * @group names
     */
    public function testGetNames()
    {
        $items = $this->srv->getNames();
        
        $this->assertCount(18, $items, 'Elements count differs from 18.');

        $itemsCopy = $items;
        sort($itemsCopy);
        
        $this->assertEquals($itemsCopy, $items, 'Elements are not sorted alphabetically.');
    }
    
    /**
     * @dataProvider getProfileDataProvider
     * @group item
     */
    public function testGetProfile($number, $expected)
    {
        $actual = $this->srv->getProfile($number);
        $this->assertEquals($expected, $actual);
    }
    
    public function setUp() {
        $this->srv = static::createClient()->getContainer()->get('disc.classical.pattern');
    }
}
