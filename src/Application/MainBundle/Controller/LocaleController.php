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
     * @Route("/switch/{locale}", name="locale.switch", requirements={"locale"="^[a-z]{2}$"})
     */
    public function changeLocaleAction(Request $request)
    {
        $locale = $request->get('locale');

        $locale = array_key_exists($locale, self:: $locales) ? $locale : self::$defaultLocale;

        $request->getSession()->set('_locale', $locale);

        $response = new Response();
        $response->headers->setCookie(new Cookie('_locale', $locale));
        $response->send();

        $referer = $request->headers->get('referer');

        if ($referer === null) {
            return $this->redirectToRoute('default.home');
        }

        return $this->redirect($referer);
    }
}
