<?php

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Behat context class.
 */
class FeatureContext extends Behat\MinkExtension\Context\MinkContext implements SnippetAcceptingContext
{

    protected static $clean = false;
    
    /**
     * @When /^(?:|że )poczekam (?P<seconds>(?:\d+)) sekund(?:|ę|y)$/
     */
    public function poczekamSekundy($seconds)
    {
        sleep($seconds);
    }
    
    /**
     * Checks, that current page response status is equal to specified.
     *
     */
    public function assertResponseStatus($code)
    {
        if ($this->getSession()->getDriver() instanceof Behat\Mink\Driver\GoutteDriver) {
            $this->assertSession()->statusCodeEquals($code);
        }
    }

    /**
     * Checks, that current page response status is not equal to specified.
     *
     */
    public function assertResponseStatusIsNot($code)
    {
        if ($this->getSession()->getDriver() instanceof Behat\Mink\Driver\GoutteDriver) {
            $this->assertSession()->statusCodeNotEquals($code);
        }
    }

    /**
     * @AfterStep
     *
     * Take screenshot when step fails.
     * Works only with Selenium2Driver.
     *
     * @param \Behat\Behat\Hook\Scope\AfterStepScope $scope
     */
    public function takeScreenshotAfterFailedStep(Behat\Behat\Hook\Scope\AfterStepScope $scope)
    {

        if (!is_dir('temp')) {
            mkdir('temp');
        }

        if (self::$clean === false) {
            $files = glob('temp/BEHAT*.png');
            array_map('unlink', $files);
            self::$clean = true;
        }

        if ($scope->getTestResult()->getResultCode() === Behat\Testwork\Tester\Result\TestResult::FAILED) {
            $driver = $this->getSession()->getDriver();
            if ($driver instanceof Behat\Mink\Driver\Selenium2Driver) {
                $browser = $this->getMinkParameters()['browser_name'];
                $feature = iconv('UTF-8', 'ASCII//TRANSLIT', $scope->getFeature()->getTitle());
                $line = $scope->getStep()->getLine();
                $description = iconv('UTF-8', 'ASCII//TRANSLIT', $scope->getStep()->getText());
                $description = preg_replace('/[^\s\w]/', '', $description);
                $image = sprintf('%s.%s.%s.%s.png', $browser, $feature, $line, $description);
                file_put_contents('temp/BEHAT.' . $image, $driver->getScreenshot());
            }
        }
    }
}
