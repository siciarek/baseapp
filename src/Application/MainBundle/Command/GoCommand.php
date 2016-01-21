<?php

namespace Application\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use utilphp\util;

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
        
        $message = __METHOD__;
        
        $phrase = 'zażółć gęślą jaźń, ZAŻÓŁĆ GĘŚLĄ JAŹŃ';
        $slug = util::slugify($phrase);
        
        $output->writeln($slug);
        
        // Release the lock:
        $lock->release();
    }

}
