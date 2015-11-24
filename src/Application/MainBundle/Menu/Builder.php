<?php

namespace Application\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Application\MainBundle\Controller\LocaleController;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware {

    /**
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, TranslatorInterface $translator, AuthorizationCheckerInterface $authorizationChecker) {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getMainMenu() {

        $config = [
            [
                'label' => 'Home',
                'route' => 'default.home',
                'icon' => 'home',
            ],
            [
                'label' => 'Info',
                'route' => 'default.info',
                'icon' => 'info-circle',
            ],
            [
                'label' => 'About',
                'route' => 'default.about',
                'icon' => 'question-circle',
            ],
            [
                'label' => 'Contact',
                'route' => 'default.contact',
                'icon' => 'envelope',
            ],
            [
                'label' => 'Private',
                'route' => 'private.index',
                'icon' => 'lock',
                'role' => 'ROLE_USER',
            ],
            [
                'label' => 'Admin',
                'route' => 'sonata_admin_dashboard',
                'icon' => 'wrench',
                'role' => 'ROLE_ADMIN',
            ],
        ];

        // Authentication menu

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $config[] = [
                'label' => 'Sign out',
                'route' => 'fos_user_security_logout',
                'icon' => 'sign-out',
                'role' => 'IS_AUTHENTICATED_REMEMBERED',
            ];
        } else {
            $config[] = [
                'label' => 'Sign in',
                'route' => 'fos_user_security_login',
                'icon' => 'sign-in',
                'role' => 'IS_AUTHENTICATED_ANONYMOUSLY',
            ];
        }


        // Locale switch menu

        $locales = [
            'label' => null,
            'icon' => 'globe',
            'children' => [],
        ];

        foreach (LocaleController::$locales as $locale => $name) {
            $locales['children'][] = [
                'label' => $name,
                'route' => 'locale.switch',
                'routeParameters' => [ 'locale' => $locale],
            ];
        }

        $config[] = $locales;
        
        return $this->generateMenu($config);
    }
    
    protected function generateMenu($config) {
        
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        foreach ($config as $key => $c) {
            
            if (!isset($c['role']) or $c['role'] === null or $this->authorizationChecker->isGranted($c['role'])) {
                $_label = $this->translator->trans($c['label']);

                $label = $_label;
                
                if (isset($c['icon']) and $c['icon'] != null and strlen($c['icon']) > 0) {
                    $label = sprintf('<i class="fa fa-%s fa-lg fa-fw"></i> %s', $c['icon'], $_label);
                }

                if (!isset($c['children']) or count($c['children']) == 0) {
                    $menu->addChild($key, [
                        'label' => $label,
                        'extras' => [ 'safe_label' => true],
                        'route' => $c['route'],
                        'routeParameters' => (isset($c['routeParameters']) and is_array($c['routeParameters'])) ? $c['routeParameters'] : [],
                        'linkAttributes' => [
                            'title' => $_label,
                        ],
                    ]);

                } else {
                    
                    $menu->addChild($key, [
                        'label' => $label . ' <span class="caret"></span>',
                        'extras' => [ 'safe_label' => true],
                        'uri' => '#',
                        'attributes' => [ ],
                        'linkAttributes' => [
                            'title' => $_label,
                            'class' => 'dropdown dropdown-toggle',
                            'data-toggle' => 'dropdown',
                            'role' => 'button',
                            'aria-haspopup' => 'true',
                            'aria-expanded' => 'true',
                        ],
                        'childrenAttributes' => [
                            'class' => 'dropdown-menu',
                        ],
                    ]);

                    foreach ($c['children'] as $chkey => $ch) {
                        if (!isset($ch['role']) or $ch['role'] === null or $this->authorizationChecker->isGranted($ch['role'])) {
                         
                            $_chlabel = $this->translator->trans($ch['label']);

                            $chlabel = $_chlabel;
                            
                            if (isset($ch['icon']) and $ch['icon'] != null and strlen($ch['icon']) > 0) {
                                $chlabel = sprintf('<i class="fa fa-%s fa-lg fa-fw"></i> %s', $ch['icon'], $_chlabel);
                            }

                            $menu[$key]->addChild($chkey, [
                                'label' => $chlabel,
                                'extras' => [ 'safe_label' => true],
                                'route' => $ch['route'],
                                'routeParameters' => (isset($ch['routeParameters']) and is_array($ch['routeParameters'])) ? $ch['routeParameters'] : [],
                                'linkAttributes' => [
                                    'title' => $_chlabel,
                                ],
                            ]);
                        }
                    }
                }
            }
        }

        return $menu;
    }

}
