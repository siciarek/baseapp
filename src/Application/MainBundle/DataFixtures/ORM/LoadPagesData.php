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
            'translation_domain' => 'ApplicationMainBundle',
            'icon' => 'ambulance',
            'role' => 'ROLE_USER',
            'children' => [
                [
                    'label' => 'First aid',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'first-aid',
                    ]
                ],
                [
                    'label' => 'Second aid',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'second-aid',
                    ]
                ]
            ]
        ],
        [
            'label' => 'Info',
            'translation_domain' => 'ApplicationMainBundle',
            'icon' => 'info-circle',
            'role' => 'ROLE_USER',
            'children' => [
                [
                    'label' => 'First info',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'first-info',
                    ]
                ],
                [
                    'label' => 'Second info',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'second-info',
                    ]
                ],
                [
                    'label' => 'Third info',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'third-info',
                    ]
                ],                
            ]
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('pl_PL');

        foreach (self::$menu as $group) {
            $g = new E\PageGroup();
            $g->setIcon($group['icon']);
            $g->translate('pl')->setName($group['label']);
            $g->mergeNewTranslations();
            $manager->persist($g);

            foreach ($group['children'] as $page) {
                $title = $page['label'];

                $temp = array_map(function($e) {
                    $temp = explode(' ', $e);
                    $header = reset($temp);
                    
                    return sprintf('<h3>%s</h3><p>%s</p>', $header, $e);
                }, $faker->paragraphs(5));
                $content = implode("\n", $temp);
                
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
