<?php

namespace Tests\Application\MainBundle\Common;

use Tests\Application\MainBundle\TestCase;

class ParameterTest extends TestCase
{
        
    /**
     * @var \Application\MainBundle\Common\Parameter
     */
    protected $srv;
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
                ->findOneByUsername(self::TEST_USER)
        ;
        $parame = $srv->get($entity, 'dummy');
    }

    /**
     * @group single
     * @group param
     */
    public function testSetRemove()
    {
        
        $srv = $this->container->get('eparam');
        $entity = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('Application\Sonata\UserBundle\Entity\User')
                ->findOneByUsername(self::TEST_USER)
        ;

        $this->assertInstanceOf('Application\Sonata\UserBundle\Entity\User', $entity);

        $this->assertCount(0, $srv->getList($entity));
        $srv->set($entity, 'test', 123);
        $this->assertCount(1, $srv->getList($entity));
        $srv->remove($entity, 'test');
        $this->assertCount(0, $srv->getList($entity));
    }

    /**
     * @group param
     */
    public function testSetGetRemoveList()
    {
        $srv = $this->container->get('eparam');
        $entity = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('Application\Sonata\UserBundle\Entity\User')
                ->findOneByUsername(self::TEST_USER)
        ;
        $this->assertInstanceOf('Application\Sonata\UserBundle\Entity\User', $entity);
        $samples = [
            ['name' => 'first', 'value' => 123, 'group' => 'general'],
            ['name' => 'second', 'value' => 246, 'group' => 'general'],
            ['name' => 'third', 'value' => 555, 'group' => 'general'],
        ];

        foreach (range(1, 100) as $i) {
            foreach ($samples as $par) {
                $srv->set($entity, $par['name'], $par['value']);
            }
        }

        $given = [];

        foreach ($samples as $par) {
            $given[] = [
                'name' => $par['name'],
                'value' => $srv->get($entity, $par['name']),
                'group' => 'general',
            ];
        }

        $this->assertEquals($given, $samples);

        $list = $srv->getList($entity, false);

        foreach ($list as $p) {
            $this->assertInstanceOf('\Application\MainBundle\Entity\Parameter', $p);
        }

        $names = array_map(function($e) {
            return $e['name'];
        }, $samples);

        while (true) {
            $list = $srv->getList($entity);

            $this->assertCount(count($names), $list);

            if (count($list) === 0) {
                break;
            }

            $name = array_pop($names);
            $srv->remove($entity, $name);
        }

        // After the removal:

        foreach (range(1, 100) as $i) {
            foreach ($samples as $par) {
                $srv->set($entity, $par['name'], $par['value']);
            }
        }

        $given = [];

        foreach ($samples as $par) {

            $given[] = [
                'name' => $par['name'],
                'value' => $srv->get($entity, $par['name']),
                'group' => 'general',
            ];
        }

        $this->assertEquals($given, $samples);
    }

    public function tearDown()
    {
        
    }

    public function setUp()
    {

        parent::setUp();
        
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('ApplicationMainBundle:Parameter');

        $entity = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('Application\Sonata\UserBundle\Entity\User')
                ->findOneByUsername(self::TEST_USER)
        ;

        $criteria = [
            'entityType' => get_class($entity),
            'entityId' => $entity->getId(),
        ];

        $stmt = $em->getConnection()
                ->prepare('DELETE FROM parameter WHERE entity_type = :entityType AND entity_id = :entityId');
        $stmt->execute($criteria);


        $em->flush();
    }

    protected function getContainer()
    {
        return $this->container;
    }

}
