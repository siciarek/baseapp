<?php

namespace Application\MainBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TestCase extends WebTestCase
{
    const TEST_USER = 'system';

    protected $container;
    
    public function tearDown()
    {
        
    }

    public function setUp()
    {

        if($this->container === null) {
            self::bootKernel();
            $this->container = self::$kernel->getContainer();
        }
        
        /**
         * @var Sonata\UserBundle\Entity\UserManager $mngr
         */
        $mngr = $this->getContainer()->get('fos_user.user_manager');

        $entity = $mngr->findOneBy(['username' => 'system']);
        
        $token = new UsernamePasswordToken($entity, null, 'main', $entity->getRoles());
        $this->getContainer()->get('security.token_storage')->setToken($token);
    }

    protected function getContainer()
    {
        return $this->container;
    }
}
