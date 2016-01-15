<?php

namespace Application\MainBundle\Tests\Common;

use Application\MainBundle\Tests\TestCase;
use Application\MainBundle\Common\EmailSender;

class EmailSenderTest extends TestCase {

    /**
     * @var \Application\MainBundle\Common\EmailSender
     */
    protected $srv;
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

        $command = sprintf('%s %s %s', PHP_BINARY, $this->getContainer()->get('kernel')->getRootDir() . '/console', '--no-ansi swiftmailer:spool:send --env=test');

        $result = trim(`$command`);

        $this->assertRegExp('/ 0 emails sent$/', $result);

        $this->assertTrue($this->srv->send());

        $result = trim(`$command`);

        $this->assertRegExp('/ 1 emails sent$/', $result);
    }

    public function setUp() {

        parent::setUp();
        $this->srv = $this->getContainer()->get('app.common.email.sender');
        $this->data = [];
    }
}
