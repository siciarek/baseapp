<?php

namespace Tests\Application\MainBundle\Common\Utils;

use Tests\Application\MainBundle\TestCase;

class UrlTest extends TestCase {

    /**
     * @var \Application\MainBundle\Common\Utils\Url
     */
    protected $srv;
    protected $data = [];

    public static function methodsDataProvider() {
        return [
            [ 'getData' ],
            [ 'getScheme' ],
            [ 'getHost' ],
            [ 'getPort' ],
            [ 'getUser' ],
            [ 'getPass' ],
            [ 'getPath' ],
            [ 'getQuery' ],
            [ 'getFragment' ],
            [ 'getTld' ],
        ];
    }
    
    public static function complexDataProvider() {
        return [
            [
                'https://maska.merlinx.pl/mask.php?SID=7bbfc9bgacbab5bg1eg7hba98672&faction_img=&amp;ext_config=&amp;openinfo=&fscrollbackward=&fscrollforward=&fagency=&fsrvcProvider=NPL&ftravelType=AUTO&new_action=&navigation_name=&navigation_value=&mail=&inbuffer=ESS480001052846271001012P205001003002002NPL+AUTO2++P15992+++77215955+01%1B5106SPR%3AO%1B5010TEST1%1B5025TEST%1B5026UL.+EJSMONDA+4%2F35%1B501393-249+++LODZ%1B5046%2B48603173114++++P%1B5010031016+++D%1B505501H++SPU+49560+A%1B50062SAI++1++0110162+++++1-2%1B5013XX%1B501202V++00100+B%1B50162++0110162+++++1-2%2FUBEZPIECZENIXX%1B501203DOKEDOC%1B5050XX%1B5999%1B5107E3101027+Keine+Berechtigung+zum+Umbuchen+dieses+Vorgangs%1B5024OPTION+BIS+120216++++GEB.-AKT%1B5013REISEPR.%1B5013KTO.%1B581301HTEST1%2FTEST%1B5010211066%1B500902HTEST2%2FTEST%1B5010010236%1B56170015+Anzeigemodus%3A+stornierter+Vorgang+77215955%1B5028D+NPL+AUTO%1B5018A%40A.PL%1B5764&outbuffer=ESS4800010T1846271001012P001001000000PLNPL+D++EQ02NPL+++++0%1B501177215955+++UNDEFINED%1B5999%1B5999%1B5840&new_bookmark=&bookmark=&del_bookmark=&reload=&prodid=P&server_nr=&s_profitool=&filler_h=&lang=PL&fbgcolor=%23DDDDDD&fmlStartAgencyNr=846271&fmlBusinessUnitNr=001&fmlStartTerminalNr=012&funiqid=&filler=&fmlSrvcProvider=NPL&fmlTravelType=AUTO&faction=D&fmlagency=P15992&fpersonCnt=2&frsrvNo=77215955&fmoduleNo=01&multifunc=&multifunc_save=&expedient=EQ02&consultant=&fservices%5B0%5D%5Bmark%5D=&fservices%5B0%5D%5Btype%5D=H&fservices%5B0%5D%5Bservice%5D=SPU+49560+A&fservices%5B0%5D%5Baccom%5D=2SAI&fservices%5B0%5D%5Boccup%5D=&fservices%5B0%5D%5BsrvCnt%5D=1&fservices%5B0%5D%5BstartDate%5D=011016&fservices%5B0%5D%5BendDate%5D=2&fservices%5B0%5D%5Balloc%5D=1-2&fservices%5B0%5D%5Bstatus%5D=XX&fservices%5B0%5D%5Bprice%5D=0.00&fservices%5B1%5D%5Bmark%5D=&fservices%5B1%5D%5Btype%5D=V&fservices%5B1%5D%5Bservice%5D=00100+B&fservices%5B1%5D%5Baccom%5D=&fservices%5B1%5D%5Boccup%5D=&fservices%5B1%5D%5BsrvCnt%5D=2&fservices%5B1%5D%5BstartDate%5D=011016&fservices%5B1%5D%5BendDate%5D=2&fservices%5B1%5D%5Balloc%5D=1-2%2FUBEZPIECZENI&fservices%5B1%5D%5Bstatus%5D=XX&fservices%5B1%5D%5Bprice%5D=0.00&fservices%5B2%5D%5Bmark%5D=&fservices%5B2%5D%5Btype%5D=DOK&fservices%5B2%5D%5Bservice%5D=EDOC&fservices%5B2%5D%5Baccom%5D=&fservices%5B2%5D%5Boccup%5D=&fservices%5B2%5D%5BsrvCnt%5D=&fservices%5B2%5D%5BstartDate%5D=&fservices%5B2%5D%5BendDate%5D=&fservices%5B2%5D%5Balloc%5D=&fservices%5B2%5D%5Bstatus%5D=XX&fservices%5B2%5D%5Bprice%5D=0.00&fservices%5B3%5D%5Bmark%5D=&fservices%5B3%5D%5Btype%5D=&fservices%5B3%5D%5Bservice%5D=&fservices%5B3%5D%5Baccom%5D=&fservices%5B3%5D%5Boccup%5D=&fservices%5B3%5D%5BsrvCnt%5D=&fservices%5B3%5D%5BstartDate%5D=&fservices%5B3%5D%5BendDate%5D=&fservices%5B3%5D%5Balloc%5D=&fservices%5B3%5D%5Bstatus%5D=&fservices%5B3%5D%5Bprice%5D=&fservices%5B4%5D%5Bmark%5D=&fservices%5B4%5D%5Btype%5D=&fservices%5B4%5D%5Bservice%5D=&fservices%5B4%5D%5Baccom%5D=&fservices%5B4%5D%5Boccup%5D=&fservices%5B4%5D%5BsrvCnt%5D=&fservices%5B4%5D%5BstartDate%5D=&fservices%5B4%5D%5BendDate%5D=&fservices%5B4%5D%5Balloc%5D=&fservices%5B4%5D%5Bstatus%5D=&fservices%5B4%5D%5Bprice%5D=&fservices%5B5%5D%5Bmark%5D=&fservices%5B5%5D%5Btype%5D=&fservices%5B5%5D%5Bservice%5D=&fservices%5B5%5D%5Baccom%5D=&fservices%5B5%5D%5Boccup%5D=&fservices%5B5%5D%5BsrvCnt%5D=&fservices%5B5%5D%5BstartDate%5D=&fservices%5B5%5D%5BendDate%5D=&fservices%5B5%5D%5Balloc%5D=&fservices%5B5%5D%5Bstatus%5D=&fservices%5B5%5D%5Bprice%5D=&action=D&startService=&startDate=&endService=&endDate=&occup=&service=&accom=&cnt=&remark=%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0SPR%3AO&fpersons%5B0%5D%5Bsex%5D=H&fpersons%5B0%5D%5Bname%5D=TEST1%2FTEST&fpersons%5B0%5D%5BageDate%5D=211066&fpersons%5B0%5D%5Bprice%5D=0.00&fpersons%5B1%5D%5Bsex%5D=H&fpersons%5B1%5D%5Bname%5D=TEST2%2FTEST&fpersons%5B1%5D%5BageDate%5D=010236&fpersons%5B1%5D%5Bprice%5D=0.00&fpersons%5B2%5D%5Bsex%5D=&fpersons%5B2%5D%5Bname%5D=&fpersons%5B2%5D%5BageDate%5D=&fpersons%5B2%5D%5Bprice%5D=&fpersons%5B3%5D%5Bsex%5D=&fpersons%5B3%5D%5Bname%5D=&fpersons%5B3%5D%5BageDate%5D=&fpersons%5B3%5D%5Bprice%5D=&fpersons%5B4%5D%5Bsex%5D=&fpersons%5B4%5D%5Bname%5D=&fpersons%5B4%5D%5BageDate%5D=&fpersons%5B4%5D%5Bprice%5D=&fpersons%5B5%5D%5Bsex%5D=&fpersons%5B5%5D%5Bname%5D=&fpersons%5B5%5D%5BageDate%5D=&fpersons%5B5%5D%5Bprice%5D=&fclient%5BclName%5D=TEST1&fclient%5BclSurname%5D=TEST&fclient%5BclAddInfo%5D=A%40A.PL&fclient%5BclAddr%5D=UL.+EJSMONDA+4%2F35&fclient%5BclZip%5D=93-249&fclient%5BclCity%5D=LODZ&fclient%5BclTel%5D=%2B48603173114&fclient%5BclTelType%5D=P&fclient%5BclDeposit%5D=&user_show=1',
                true,
                'SID',
            ],
        ];
    }

    public static function simpleDataProvider() {
        return [
            [
                null,
                false,
                [
                    'scheme' => null,
                    'host' => null,
                    'port' => null,
                    'user' => null,
                    'pass' => null,
                    'path' => null,
                    'query' => [],
                    'fragment' => null,
                    'tld' => null,
                ],
            ],
            [
                'https://gmail.com',
                true,
                [
                    'scheme' => 'https',
                    'host' => 'gmail.com',
                    'port' => null,
                    'user' => null,
                    'pass' => null,
                    'path' => null,
                    'query' => [],
                    'fragment' => null,
                    'tld' => 'com',
                ],
            ],
            [
                'http://wp.pl',
                true,
                [
                    'scheme' => 'http',
                    'host' => 'wp.pl',
                    'port' => null,
                    'user' => null,
                    'pass' => null,
                    'path' => null,
                    'query' => [],
                    'fragment' => null,
                    'tld' => 'pl',
                ],
            ],
            [
                'https://colak:helloworld@test.domain.org:8080/path/to/script.php?jeden=23&lista[]=jurek&lista[]=piotrek&amp;lista[]=czarek#kitten',
                true,
                [
                    'scheme' => 'https',
                    'host' => 'test.domain.org',
                    'port' => 8080,
                    'user' => 'colak',
                    'pass' => 'helloworld',
                    'path' => '/path/to/script.php',
                    'query' => [
                        'jeden' => 23,
                        'lista' => ['jurek', 'piotrek', 'czarek',],
                    ],
                    'fragment' => 'kitten',
                    'tld' => 'org',
                ],
            ],
        ];
    }

    /**
     * @group url
     */
    public function testGeneral() {
        foreach (['app.url', 'xurl'] as $name) {
            $this->assertInstanceOf('Application\MainBundle\Common\Utils\Url', $this->getContainer()->get($name));
        }
    }

    /**
     * @group url
     * @dataProvider simpleDataProvider
     */
    public function testSimple($url, $parseResult, $data) {
        $result = $parseResult === true ? $this->srv : null;
        $this->assertEquals($result, $this->srv->parse($url));
        $this->assertEquals($data, $this->srv->getData());
    }

    /**
     * @group url
     * @dataProvider complexDataProvider
     */
    public function testComplex($url, $parseResult, $expected) {
        $result = $parseResult === true ? $this->srv : null;
        $this->assertEquals($result, $this->srv->parse($url));
        $this->assertArrayHasKey($expected, $this->srv->getQuery());
    }

    /**
     * @group url
     * @group exception
     * @expectedException \Application\MainBundle\Common\Utils\InvalidUrlException
     * @expectedExceptionMessage Invalid url.
     */
    public function testStrictMode() {
        $url = null;
        $this->srv->parse($url, true);
    }
    
    /**
     * @group url
     * @group exception
     * @dataProvider methodsDataProvider
     * @expectedException \Exception
     * @expectedExceptionMessage Use parse() method.
     */
    public function testUninitializedData($method) {
        $url = null;
        $this->srv->$method();
    }
    
    /**
     * @group url
     * @group exception
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage Call to undefined method Application\MainBundle\Common\Utils\Url::getDummy
     */
    public function testInvalidMethod() {
        $url = 'http://google.com';
        $this->srv->parse($url);
        $this->srv->getDummy();
    }
    
    public function setUp() {
        parent::setUp();

        $this->srv = $this->getContainer()->get('app.url');
    }

    protected function getContainer() {
        return $this->container;
    }

}
