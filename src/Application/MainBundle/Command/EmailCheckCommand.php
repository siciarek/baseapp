<?php

namespace Application\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class EmailCheckCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('email:check')
                ->setDescription('Email sender check command.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $sender = $this->getContainer()->get('app.common.email.sender');
        
        $to = [
            'name' => 'Jacek Siciarek',
            'email' => 'siciarek@gmail.com',
        ];
        
        $from = [
            'name' => 'Jacek Siciarek',
            'email' => 'siciarek@gmail.com',
        ];

        $params = [
            'user' => 'Czesław Olak',
            'date' => new \DateTime('1966-10-21 15:10:00'),
        ];

        $template = '::email/test.html.twig';
        $message = $sender->getMessage($template, $params, $to, $from);
        $sender->send($message);

        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'swiftmailer:spool:send',
            '--message-limit' => 1,
        ]);

        $application->run($input, $output);
    }

}
