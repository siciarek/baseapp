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
    protected $data = [];
    
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

        $this->data['before'] = trim(`app/console swiftmailer:spool:send --env=test`);        
        $this->assertRegExp('/ 0 emails sent$/', $this->data['before']);
        
        $this->assertTrue($this->srv->send());

        $this->data['after'] = trim(`app/console swiftmailer:spool:send --env=test`);
        $this->assertRegExp('/ 1 emails sent$/', $this->data['after']);        
    }

    public function tearDown() {

    }
    
    public function setUp() {

        echo `ant ccx`;
        echo `app/console cache:warmup --env=test`;
        
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->srv = $this->container->get('app.common.email.sender');
        $this->data = [];
    }   
}
