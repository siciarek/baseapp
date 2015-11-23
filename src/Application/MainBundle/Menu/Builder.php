<?php
namespace Application\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\DataCollectorTranslator;

class Builder
{
    private $factory;
    private $translator;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, DataCollectorTranslator $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }

    public function getMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', [ 'route' => 'default.home', 'label' => $this->translator->trans('Home') ]);
        $menu->addChild('Info', [ 'route' => 'default.info', 'label' =>  $this->translator->trans('Info') ]);
        $menu->addChild('About', [ 'route' => 'default.about', 'label' =>  $this->translator->trans('About') ]);
        $menu->addChild('Contact', [ 'route' => 'default.contact', 'label' =>  $this->translator->trans('Contact') ]);

        return $menu;
    }
}
