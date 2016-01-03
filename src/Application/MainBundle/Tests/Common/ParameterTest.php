<?php

namespace Application\MainBundle\Tests\Common;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Application\MainBundle\Common\Parameter;

class ParameterTest extends WebTestCase
{

    /**
     * @var \Application\MainBundle\Common\EmailSender
     */
    protected $srv;
    protected $container;
    protected $data = [];

    /**
     * @group param
     */
    public function testGeneral()
    {
        foreach (['app.entity.parameter', 'eparam'] as $name) {
            $this->assertInstanceOf('Application\MainBundle\Common\Parameter', $this->container->get($name));
        }
    }

    /**
     * @group exception
     * @group param
     * 
     * @expectedException        Exception
     * @expectedExceptionMessage Parameter not found.
     */
    public function testExceptionHasRightMessage()
    {
        $srv = $this->container->get('eparam');
        $entity = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('Application\Sonata\UserBundle\Entity\User')
                ->findOneByUsername('colak')
        ;
        $parame = $srv->get($entity, 'dummy');
    }
    
    /**
     * @group param
     */
    public function testSetGetRemoveList()
    {
        $srv = $this->container->get('eparam');
        $entity = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('Application\Sonata\UserBundle\Entity\User')
                ->findOneByUsername('colak')
        ;
        $this->assertInstanceOf('Application\Sonata\UserBundle\Entity\User', $entity);
        $samples = [
            'first' => 123,
            'second' => 246,
            'third' => 555,
        ];

        foreach (range(1, 100) as $i) {
            foreach ($samples as $name => $value) {
                $srv->set($entity, $name, $value);
            }
        }

        $given = [];

        foreach ($samples as $name => $value) {
            $given[$name] = $srv->get($entity, $name);
        }

        $this->assertEquals($given, $samples);

        $list = $srv->getList($entity, false);
        
        foreach($list as $p) {
            $this->assertInstanceOf('\Application\MainBundle\Entity\Parameter', $p);
        }

        $names = array_keys($samples);

        while (count($names) > 0) {
            $list = $srv->getList($entity);

            $this->assertCount(count($names), $list);

            if (count($list) === 0) {
                break;
            }

            $name = array_pop($names);
            $srv->remove($entity, $name);
        };
        
        // After the removal:
        
        foreach (range(1, 100) as $i) {
            foreach ($samples as $name => $value) {
                $srv->set($entity, $name, $value);
            }
        }

        $given = [];

        foreach ($samples as $name => $value) {
            $given[$name] = $srv->get($entity, $name);
        }

        $this->assertEquals($given, $samples);
    }

    public function tearDown()
    {
        
    }

    public function setUp()
    {

        self::bootKernel();
        $this->container = self::$kernel->getContainer();
    }

    protected function getContainer()
    {
        return $this->container;
    }

}
