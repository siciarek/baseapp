<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Application\MainBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Application\MainBundle\Entity as E;

class LoadPagesData extends BasicFixture
{

    /**
     * @var numeric 
     */
    protected $order = 3;

    public static $menu = [
        [
            'label' => 'Help',
            'icon' => 'ambulance',
            'role' => 'ROLE_USER',
            'children' => [
                [
                    'label' => 'First aid',
                    'role' => 'ROLE_USER',
                    'route' => 'default.page',
                    'routeParameters' => [
                        'slug' => 'first-aid',
                    ]
                ],
                [
                    'label' => 'Second aid',
                    'role' => 'ROLE_USER',
                    'route' => 'default.page',
                    'routeParameters' => [
                        'slug' => 'second-aid',
                    ]
                ]
            ]
        ],
        [
            'label' => 'Info',
            'icon' => 'ambulance',
            'role' => 'ROLE_USER',
            'children' => [
                [
                    'label' => 'First info',
                    'role' => 'ROLE_USER',
                    'route' => 'default.page',
                    'routeParameters' => [
                        'slug' => 'first-info',
                    ]
                ],
                [
                    'label' => 'Second info',
                    'role' => 'ROLE_USER',
                    'route' => 'default.page',
                    'routeParameters' => [
                        'slug' => 'second-info',
                    ]
                ]
            ]
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('pl_PL');
         
        foreach(self::$menu as $group) {
            $g = new E\PageGroup();
            $g->setIcon($group['icon']);
            $g->translate('pl')->setName($group['label']);
            $g->mergeNewTranslations();
            $manager->persist($g);
            
            foreach($group['children'] as $page) {
                $content = $faker->paragraphs(5, true);
                $content = nl2br($content);
                $title = $page['label'];
                
                $p = new E\Page();
                $p->setName($page['label']);
                $p->translate('pl')->setTitle($title);
                $p->translate('pl')->setContent($content);
                $p->mergeNewTranslations();
                $p->setGroup($g);
                $manager->persist($p);
            }
        }
        
        $manager->flush();
    }

}
