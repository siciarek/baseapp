<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @Route("/locale")
 */
class LocaleController extends Controller
{
    public static $locales = array(
        'pl' => 'Polski',
        'en' => 'English',
    );

    public static $defaultLocale = 'en';

    /**
     * @Route("/switch/{_locale}", name="locale.switch", requirements={"_locale"="^[a-z]{2}$"})
     */
    public function changeLocaleAction(Request $request)
    {
        $locale = $request->get('_locale');
        $locale = array_key_exists($locale, self:: $locales) ? $locale : self::$defaultLocale;

        // Store locale in session:
        $request->getSession()->set('_locale', $locale);

        // Store locale in cookie:
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P1Y')); // One year expiration time
        $cookie = new Cookie('_locale', $locale, $expire, '/', null, false, false);
        
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send();

        // Redirect to the referer:
        $referer = $request->headers->get('referer');

        // Or to the homepage if the referer is not available:
        if ($referer === null) {
            return $this->redirectToRoute('default.home');
        }

        return $this->redirect($referer);
    }
}
