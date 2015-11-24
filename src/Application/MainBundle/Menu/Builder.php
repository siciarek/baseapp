<?php
namespace Application\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Application\MainBundle\Controller\LocaleController;

class Builder
{
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
    public function __construct(FactoryInterface $factory, TranslatorInterface $translator, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory              = $factory;
        $this->translator           = $translator;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getMainMenu()
    {
        $config = [
            [
                'label'       => 'Home',
                'route'       => 'default.home',
                'routeParams' => [ ],
                'icon'        => 'home',
                'role'        => 'IS_AUTHENTICATED_ANONYMOUSLY',
                'children'    => [ ]
            ],
            [
                'label'       => 'Info',
                'route'       => 'default.info',
                'routeParams' => [ ],
                'icon'        => 'info-circle',
                'role'        => 'IS_AUTHENTICATED_ANONYMOUSLY',
                'children'    => [ ]
            ],
            [
                'label'       => 'About',
                'route'       => 'default.about',
                'routeParams' => [ ],
                'icon'        => 'question-circle',
                'role'        => 'IS_AUTHENTICATED_ANONYMOUSLY',
                'children'    => [ ]
            ],
            [
                'label'       => 'Contact',
                'route'       => 'default.contact',
                'routeParams' => [ ],
                'icon'        => 'envelope',
                'role'        => 'IS_AUTHENTICATED_ANONYMOUSLY',
                'children'    => [ ]
            ],
            [
                'label'       => 'Private',
                'route'       => 'private.index',
                'routeParams' => [ ],
                'icon'        => 'lock',
                'role'        => 'ROLE_USER',
                'children'    => [ ]
            ],
            [
                'label'       => 'Admin',
                'route'       => 'private.index',
                'routeParams' => [ ],
                'icon'        => 'wrench',
                'role'        => 'ROLE_ADMIN',
                'children'    => [ ]
            ],
        ];

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        foreach ($config as $key => $c) {
            if ($this->authorizationChecker->isGranted($c['role'])) {
                $label = $this->translator->trans($c['label'];
                if (isset($c['icon']) and $c['icon'] != null and strlen($c['icon']) > 9) {
                    $label = sprintf('<i class="fa fa-%s fa-lg fa-fw"></i> %s', $c['icon'], $label));
                }
                $menu->addChild($key, [ 'route' => $c['route'], 'routeParams' => $c['routeParams'], 'label' => $label, 'extras' => [ 'safe_label' => true ] ]);
            }
        }

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild('Sign out', [ 'route' => 'fos_user_security_logout', 'label' => $this->translator->trans('Sign out') ]);
        } else {
            $menu->addChild('Sign in', [ 'route' => 'fos_user_security_login', 'label' => $this->translator->trans('Sign in') ]);
        }

        $menu->addChild('Locale', [
            'uri'                => '#',
            'label'              => '<i class="fa fa-globe fa-lg fa-fw"></i> <span class="caret"></span>',
            'extras'             => [ 'safe_label' => true ],
            'attributes'         => [ ],
            'linkAttributes'     => [
                'class'         => 'dropdown dropdown-toggle',
                'data-toggle'   => 'dropdown',
                'role'          => 'button',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'true',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu',
            ],
        ]);

        foreach (LocaleController::$locales as $locale => $name) {
            $menu['Locale']->addChild($name, [ 'route' => 'locale.switch', 'routeParameters' => [ 'locale' => $locale ] ]);
        }

        return $menu;
    }
}
