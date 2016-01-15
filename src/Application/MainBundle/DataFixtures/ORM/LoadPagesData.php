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
            'enabled' => true,
            'label' => 'Help',
            'translation_domain' => 'ApplicationMainBundle',
            'icon' => 'ambulance',
            'role' => 'ROLE_USER',
            'children' => [
                [
                    'enabled' => true,
                    'label' => 'First aid',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'help/first-aid.html',
                    ]
                ],
                [
                    'enabled' => true,
                    'label' => 'Last aid',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'help/last-aid.html',
                    ]
                ]
            ]
        ],
        [
            'enabled' => true,
            'label' => 'Info',
            'translation_domain' => 'ApplicationMainBundle',
            'icon' => 'info-circle',
            'role' => 'ROLE_USER',
            'children' => [
                [
                    'enabled' => true,
                    'label' => 'First info',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'info/first-info.html',
                    ]
                ],
                [
                    'enabled' => true,
                    'label' => 'Second info',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'info/second-info.html',
                    ]
                ],
                [
                    'enabled' => true,
                    'label' => 'Third info',
                    'translation_domain' => 'ApplicationMainBundle',
                    'role' => 'ROLE_USER',
                    'route' => 'page.index',
                    'routeParameters' => [
                        'slug' => 'info/third-info.html',
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

        foreach (self::$menu as $category) {
            $g = new E\PageCategory();
            $g->setIcon($category['icon']);
            $g->setName($category['label']);
            $g->translate('pl')->setTitle($category['label']);
            $g->mergeNewTranslations();
            $manager->persist($g);

            foreach ($category['children'] as $page) {
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
                $p->setCategory($g);
                $manager->persist($p);
            }
        }

        $manager->flush();
    }
}
