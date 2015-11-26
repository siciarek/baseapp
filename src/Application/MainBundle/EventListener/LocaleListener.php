<?php
namespace Application\MainBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        // TODO: make tidy (refactor it):
        
        // if (!$request->hasPreviousSession()) {
        //     return;
        // }
        
        if ($locale = $request->attributes->get('locale')) {
            $request->getSession()->set('locale', $locale);
            $request->setLocale($locale);
            return;
        }
        
        if($request->cookies->has('locale')) {
            $locale = $request->cookies->get('locale');
            $request->getSession()->set('locale', $locale);
            $request->setLocale($locale);
            return;
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}