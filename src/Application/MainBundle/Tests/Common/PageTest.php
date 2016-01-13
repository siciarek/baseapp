<?php

namespace Application\MainBundle\Tests\Common;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PageTest extends WebTestCase
{

    /**
     * @var \Application\MainBundle\Common\EmailSender
     */
    protected $srv;
    protected $container;
    protected $data = [];

    /**
     * @group paget
     */
    public function testGeneral()
    {
        foreach (['app.pages', 'pages'] as $name) {
            $this->assertInstanceOf('Application\MainBundle\Common\Page', $this->getContainer()->get($name));
        }
    }

    /**
     * @group paget
     */
    public function testGetMenu()
    {
        $this->srv = $this->getContainer()->get('pages');
        $actual = $this->srv->getMenu();
        
        $this->assertTrue(is_array($actual));
    }
    
    public function tearDown()
    {
        
    }

    public function setUp()
    {

        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $em = $this->container->get('doctrine.orm.entity_manager');

        $entity = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('Application\Sonata\UserBundle\Entity\User')
                ->findOneByUsername('system')
        ;
        $token = new UsernamePasswordToken($entity, null, 'main', $entity->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
    }

    protected function getContainer()
    {
        return $this->container;
    }

}
