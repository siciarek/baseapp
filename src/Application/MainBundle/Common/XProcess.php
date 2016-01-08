<?php

namespace Application\MainBundle\Common;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class XProcess implements ContainerAwareInterface
{
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

    public function __construct(ContainerInterface $container, $name = 'ls')
    {
        $this->setContainer($container);

        $this->setApplication($name);
    }

    public function run($command)
    {
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

    public function getApplication()
    {
        if ($this->application === null) {
            throw new \Exception('Application is not available.');
        }

        return $this->application;
    }

    public function setApplication($name)
    {
        $application = $name;
        $msg = 'Application is not available.';

        if (preg_match('/^WIN/i', PHP_OS) === 0) {
            $output = $this->run('whereis '.$name);

            $list = explode(' ', $output);
            array_shift($list);
            $list = array_map('trim', $list);

            $list = array_filter($list, function ($e) use ($name) {
                return preg_match('/\b'.$name.'$/', $e) > 0;
            });

            $list = array_values($list);

            if (count($list) == 0) {
                throw new \Exception($msg);
            }

            $application = null;

            foreach ($list as $app) {
                if (file_exists($app) and is_file($app) and is_executable($app)) {
                    $application = $app;
                    break;
                }
            }

            if ($application === null) {
                throw new \Exception($msg);
            }
        }

        if (!file_exists($application) or !is_file($application) or !is_executable($application)) {
            throw new \Exception($msg);
        }

        $this->application = $application;
    }

    public function getVersion()
    {
        $cmd = sprintf('%s --version', $this->getApplication());
        $output = $this->run($cmd);

        if (preg_match('/\s\d+\.\d+\.\d+\s/', $output, $match) == 0) {
            return;
        }

        $version = trim($match[0]);

        return $version;
    }

    /**
     * Gets the container.
     *
     * @return ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
