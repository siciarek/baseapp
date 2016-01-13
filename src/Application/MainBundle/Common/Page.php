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
        
        foreach($groups as $g) {
            $gr = [
                'label' => $g->getName(),
                'translation_domain' => 'ApplicationMainBundle',
                'icon' => $g->getIcon(),
                'role' => $g->getRole(),
                'children' => [],
            ];
            
            foreach($g->getPages() as $p) {
                $gr['children'][] = [
                    'label' => $p->getTitle(),
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => $p->getRole(),
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => $p->getSlug(),
                    ]
                ];
            }

            $config[] = $gr;
        }
        
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
