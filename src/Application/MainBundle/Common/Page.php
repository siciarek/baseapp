<?php

namespace Application\MainBundle\Common;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Page service
 */
class Page implements ContainerAwareInterface
{
    /**
     * Container reference variable.
     *
     * @var Container interface
     */
    protected $container;
    
    public function __construct(ContainerInterface $container = null)
    {
        $this->setContainer($container);
    }
    
    public function getMenu() {
        $config = [];

        $criteria = [
            'enabled' => true,
        ];
        
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('ApplicationMainBundle:PageGroup');
        $groups = $repo->findBy($criteria);
        
        ld($groups);
        
        return $config;
    }
    
    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
