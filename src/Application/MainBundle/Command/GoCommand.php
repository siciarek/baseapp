<?php

namespace Application\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

class GoCommand extends ContainerAwareCommand
{

    protected function configure()
    {        
        $this
                ->setName('go')
                ->setDescription('Symfony 2 command template.')
                ->addOption(
                        'yell', 'y', InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters'
                )
                ->addOption(
                        'msg', 'm', InputOption::VALUE_OPTIONAL, 'Custom message'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Create the lock:
        $lock = new LockHandler('go');
        if (!$lock->lock()) {            
            return 0xFF;
        }
        
        $messages = __METHOD__;
        $output->writeln($messages);
        
        sleep(10);
        
        // Release the lock:
        $lock->release();
    }

}
