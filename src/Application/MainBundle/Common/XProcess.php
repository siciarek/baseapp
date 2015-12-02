<?php
namespace Application\MainBundle\Common;

use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DomCrawler\Crawler;


class XProcess implements ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var string
     */
    protected $application;
    /**
     * @var string
     */
    protected $applicationName;

    public function __construct(ContainerInterface $container, $name = 'ls') {
        
        $this->setContainer($container);
        $output = $this->run('whereis ' . $name);

        $list = explode(' ', $output);
        array_shift($list);
        $list = array_map('trim', $list);

        $list = array_filter($list, function($e) use ($name) {
            return preg_match('/\b' . $name . '$/', $e) > 0;
        });

        $list = array_values($list);

        if (count($list) == 0) {
            throw new ProcessFailedException($process);
        }

        $app = null;
        
        foreach($list as $application) {
            if (file_exists($application) AND is_file($application) AND is_executable($application)) {
                $app = $application;
                break;
            }
        }
        
        
        if ($app === null) {
            throw new ProcessFailedException($process);
        }
        
        $this->setApplication($app);
    }
 
    public function run($command) {

        $process = new Process($command);
        $process->mustRun();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        $stderr = $process->getErrorOutput();
        $stderr = trim($stderr);
        
        $stdout = $process->getOutput();

        $output = implode("\n\n", [$stderr, $stdout]);
        
        return $output;
    }
    
    public function getApplication() {

        if ($this->application === null) {
            throw new \Exception('Application is not available.');
        }

        return $this->application;
    }

    public function setApplication($application) {

        if (!(file_exists($application) AND is_file($application) AND is_executable($application))) {
            throw new \Exception('Application: ' . $application . ' is not available.');
        }

        $this->application = $application;
    }

    public function getVersion() {
        $cmd = sprintf('%s --version', $this->getApplication());
        $output = $this->run($cmd);

        if (preg_match('/\s\d+\.\d+\.\d+\s/', $output, $match) == 0) {
            return null;
        }

        $version = trim($match[0]);

        return $version;
    }
    
    /**
     * Gets the container
     *
     * @return ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    
}
