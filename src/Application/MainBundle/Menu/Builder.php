<?php

namespace Application\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Application\MainBundle\Controller\LocaleController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Builder implements ContainerAwareInterface
{

    /**
     * @var \Symfony\Component\DependencyInjection\ConainerInterface
     */
    private $container;

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

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, TranslatorInterface $translator, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getMainMenu()
    {
        $config = [];

        // Add custom pages:
        $custom = $this->container->get('app.pages')->getMenu();

        // Read main menu from configuration:        
        $main = $this->container->getParameter('application_main.main_menu');

        // Add authentication menu:
        $auth = [
            [
                'label' => 'layout.login',
                'translation_domain' => 'FOSUserBundle',
                'route' => 'fos_user_security_login',
                'icon' => 'sign-in',
                'role' => 'IS_AUTHENTICATED_ANONYMOUSLY',
            ]
        ];

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            array_shift($main);

            $auth = [
                [
                    'label' => 'layout.logout',
                    'translation_domain' => 'FOSUserBundle',
                    'route' => 'fos_user_security_logout',
                    'icon' => 'sign-out',
                    'role' => 'IS_AUTHENTICATED_REMEMBERED',
                ]
            ];
        }

        $config = array_merge($config, $custom);
        $config = array_merge($config, $main);
        $config = array_merge($config, $auth);

        return $this->generateMenu($config);
    }

    protected function generateMenu($config)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        foreach ($config as $key => $c) {

            if (!(!isset($c['role']) or $c['role'] === null or $this->authorizationChecker->isGranted($c['role']))) {
                continue;
            }

            $_label = $this->translator->trans($c['label'], [], $c['translation_domain']);

            $label = $_label;

            if (isset($c['icon']) and $c['icon'] != null and strlen($c['icon']) > 0) {
                $label = sprintf('<i class="fa fa-%s fa-lg fa-fw"></i> %s', $c['icon'], $_label);
            }

            $options = [];

            if(!isset($c['enabled'])) {
                $c['enabled'] = true;
            }
            
            if (!isset($c['children']) or count($c['children']) == 0) {

                $options = [
                    'label' => $label,
                    'extras' => [ 'safe_label' => true],
                    'attributes' => [
                    ],
                    'linkAttributes' => [
                        'title' => $_label,
                    ],
                    'childrenAttributes' => []
                ];

                if (isset($c['route']) and $c['enabled'] === true) {
                    $options['route'] = $c['route'];
                    $options['routeParameters'] = (isset($c['routeParameters']) and is_array($c['routeParameters'])) ? $c['routeParameters'] : [];
                    
                } else {
                    $options['uri'] = 'javascript:void(null)';
                    $options['attributes']['class'] = 'disabled';                   
                }

                $c['children'] = [];
            } else {

                $options = [
                    'label' => $label . ' <span class="caret"></span>',
                    'extras' => [ 'safe_label' => true],
                    'uri' => '#',
                    'attributes' => [],
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
                ];
            }

            $menu->addChild($key, $options);

            foreach ($c['children'] as $chkey => $ch) {

                if (!isset($ch['role']) or $ch['role'] === null or $this->authorizationChecker->isGranted($ch['role'])) {

                    $_chlabel = $this->translator->trans($ch['label'], [], $ch['translation_domain']);

                    $chlabel = $_chlabel;

                    if (isset($ch['icon']) and $ch['icon'] != null and strlen($ch['icon']) > 0) {
                        $chlabel = sprintf('<i class="fa fa-%s fa-lg fa-fw"></i> %s', $ch['icon'], $_chlabel);
                    }
                    
                    $choptions = [
                        'label' => $chlabel,
                        'extras' => [ 'safe_label' => true],
                        'attributes' => [
                        ],
                        'linkAttributes' => [
                            'title' => $_chlabel,
                        ],
                    ];

                    if (isset($ch['route']) and $ch['enabled'] === true) {
                        $choptions['route'] = $ch['route'];
                        $choptions['routeParameters'] = (isset($ch['routeParameters']) and is_array($ch['routeParameters'])) ? $ch['routeParameters'] : [];
                    } else {
                        $choptions['uri'] = 'javascript:void(null)';
                        $choptions['attributes']['class'] = 'disabled';
                    }

                    $menu[$key]->addChild($chkey, $choptions);
                }
            }
        }

        return $menu;
    }

}
