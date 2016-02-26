<?php

namespace Application\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

class DumpDbCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('dumpdb')
                ->setDescription('Sync your local database with the remote one.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = implode(DIRECTORY_SEPARATOR, [
            $this->getContainer()->get('kernel')->getRootDir(),
            '..',
            'utils',
            'dumpdb',
        ]);

        $command = implode(DIRECTORY_SEPARATOR, [
            $dir,
            'dumpdb',
        ]);

        chdir($dir);

        $process = new \Symfony\Component\Process\Process($command);
        $process->mustRun();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $stderr = trim($process->getErrorOutput());

        $stdout = $process->getOutput();

        $result = implode("\n\n", [$stderr, $stdout]);


        $output->writeln($result);
    }

}
