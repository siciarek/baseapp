<?php

namespace Application\MainBundle\Tests\Common;

use Application\MainBundle\Tests\TestCase;

class PageTest extends TestCase
{

    /**
     * @var \Application\MainBundle\Common\EmailSender
     */
    protected $srv;
    protected $container;
    protected $data = [];

    /**
     * @group pages
     */
    public function testGeneral()
    {
        foreach (['app.pages', 'pages'] as $name) {
            $this->assertInstanceOf('Application\MainBundle\Common\Page', $this->getContainer()->get($name));
        }
    }

    /**
     * @group pages
     */
    public function testGetMenu()
    {
        $this->srv = $this->getContainer()->get('pages');
        $actual = $this->srv->getMenu();

        $expected = \Application\MainBundle\DataFixtures\ORM\LoadPagesData::$menu;

        $this->assertTrue(is_array($actual));
        $this->assertEquals($expected, $actual);
    }

}
