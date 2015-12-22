<?php

namespace Application\MainBundle\Tests\Common;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Application\MainBundle\Common\EmailSender;

class EmailSenderTest extends WebTestCase {

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
        $command = 'php app/console --no-ansi swiftmailer:spool:send --env=test';

        `ant ccx`;

        $result = trim(`$command`);

        $this->assertRegExp('/ 0 emails sent$/', $result);

        $this->assertTrue($this->srv->send());

        $result = trim(`$command`);

        $this->assertRegExp('/ 1 emails sent$/', $result);
    }

    public function tearDown() {

    }

    public function setUp() {

        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->srv = $this->container->get('app.common.email.sender');
        $this->data = [];
    }

}
