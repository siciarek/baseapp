<?php

namespace Application\MainBundle\Tests\Common;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Application\MainBundle\Common\EmailSender;

class EmailSenderTest extends WebTestCase
{
    /**
     * @var \Application\MainBundle\Common\EmailSender
     */
    protected $srv;
    protected $container;
    
    /**
     * @group sender
     */
    public function testGeneral() {
     
        $this->assertInstanceOf('Application\MainBundle\Common\EmailSender', $this->srv);
    }

    /**
     * @group sender
     */
    public function testSend() {
        
        $this->assertTrue($this->srv->send());
    }

    public function setUp() {
        
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->srv = $this->container->get('app.common.email.sender');
    }   
}
