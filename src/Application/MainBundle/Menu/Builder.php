<?php
namespace Application\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Security\Core\SecurityContext;

class Builder
{
    private $factory;
    private $translator;
    private $securityContext;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, DataCollectorTranslator $translator, SecurityContext $securityContext)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->securityContext = $securityContext;
    }

    public function getMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        
        $menu->addChild('Home', [ 'route' => 'default.home', 'label' => $this->translator->trans('Home') ]);
        $menu->addChild('Info', [ 'route' => 'default.info', 'label' =>  $this->translator->trans('Info') ]);
        $menu->addChild('About', [ 'route' => 'default.about', 'label' =>  $this->translator->trans('About') ]);
        $menu->addChild('Contact', [ 'route' => 'default.contact', 'label' =>  $this->translator->trans('Contact') ]);
        
        if($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild('Sign out', [ 'route' => 'fos_user_security_logout', 'label' =>  $this->translator->trans('Sign out') ]);
        }
        else {
            $menu->addChild('Sign in', [ 'route' => 'fos_user_security_login', 'label' =>  $this->translator->trans('Sign in') ]);
        }

        return $menu;
    }
}
