<?php

namespace Application\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('go:show')
            ->setDescription('Symfony 2 command template.')
            ->addOption(
                'yell', 'y',
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
            ->addOption(
                'msg', 'm',
                InputOption::VALUE_OPTIONAL,
                'Custom message'
            )
        ;
    }

    protected function addIdToUrl($url, $id, $idKey = '_ID') {

        $temp = parse_url($url);

        $params = [];
        
        if(array_key_exists('query', $temp)) {
            $q = $temp['query'];
            $q = preg_replace('|&amp;|', '&', $q);            
            parse_str($q, $params);
        }
        
        $params = array_merge($params, [ $idKey => $id ]);        
        $query = http_build_query($params);
        
        $url = sprintf('%s://%s?%s', $temp['scheme'], $temp['host'], $query);
        
        return $url;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = 'http://maxiehill.com?_id=777&id=&first=2334&second=456&third=444444&amp;fourth={"jeden":"dwa"}';        
//        $url = 'http://maxiehill.com';        
//        $url = 'http://maxiehill.com?';   
        
        
        $id = 'XCSD432232';
        $url = $this->addIdToUrl($url, $id);
        
        $output->writeln($url);
    }
}