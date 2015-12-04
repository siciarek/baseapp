<?php
namespace Application\MainBundle\Tests\Common;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LaafFrameTest extends WebTestCase
{
    protected $srv;
    protected $container;
    protected static $auth = [
        'login' => 'test',
        'password' => 'test',
    ];
    
    /**
     * @group laaf
     */
    public function testGetRequestFrame() {
        $frame = $this->srv->getRequestFrame();
        $this->checkKeys($frame);
        $this->assertTrue($frame['success']);

        $data = [
            'first' => 1,
            'second' => 2,
        ];
        
        $frame = $this->srv->getRequestFrame(null, $data, self::$auth);
        $this->checkKeys($frame, true);
        $this->assertTrue($frame['success']);
    }
    
    /**
     * @group laaf
     */
    public function testGetInfoFrame() {
        $frame = $this->srv->getInfoFrame();
        $this->checkKeys($frame);
        $this->assertTrue($frame['success']);

        $frame = $this->srv->getInfoFrame(null, null, self::$auth);
        $this->checkKeys($frame, true);
        $this->assertTrue($frame['success']);
    }
    
    /**
     * @group laaf
     */
    public function testGetDataFrame() {
        $frame = $this->srv->getDataFrame();
        $this->checkKeys($frame);
        $this->assertTrue($frame['success']);

        $data = [1, 2, 3];
        
        $frame = $this->srv->getDataFrame(null, $data, false, self::$auth);
        $this->checkKeys($frame, true);
        $this->assertTrue($frame['success']);
    }
    
    /**
     * @group laaf
     */
    public function testGetWarningFrame() {
        $frame = $this->srv->getWarningFrame();
        $this->checkKeys($frame);
        $this->assertFalse($frame['success']);

        $frame = $this->srv->getWarningFrame(null, null, self::$auth);
        $this->checkKeys($frame, true);
        $this->assertFalse($frame['success']);
    }
    
    /**
     * @group laaf
     */
    public function testGetErrorFrame() {
        $frame = $this->srv->getErrorFrame();
        $this->checkKeys($frame);
        $this->assertFalse($frame['success']);

        $frame = $this->srv->getWarningFrame(null, null, self::$auth);
        $this->checkKeys($frame, true);
        $this->assertFalse($frame['success']);
    }
    
    public function checkKeys($frame, $withAuth = false) {
        $keys = $withAuth === true
            ? ['success', 'type', 'datetime', 'msg', 'data', 'auth']
            : ['success', 'type', 'datetime', 'msg', 'data'];
        
        $authKeys = ['login', 'password'];
        
        foreach($keys as $key) {
            $this->assertArrayHasKey($key, $frame);
        }
        
        $this->assertRegExp('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $frame['datetime'], 'Invalid datetime format.');
        $this->assertEquals((new \DateTime($frame['datetime']))->format('Y-m-d H:i:s'), $frame['datetime'], 'Invalid datetime format.');
    
        if($withAuth === true) {
            foreach($authKeys as $key) {
                $this->assertArrayHasKey($key, $frame['auth']);
            }
        }
    }
    
    public function setUp() {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->srv = $this->container->get('app.common.laaf.frame');
    }
}
