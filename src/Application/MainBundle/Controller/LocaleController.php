<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/locale")
 */
class LocaleController extends Controller
{
    public static $locales = array(
        'pl' => 'Polski',
        'en' => 'English',
        'de' => 'Deutsch',
    );

    protected $default_locale = 'en';

    /**
     * @Route("/switch/{locale}", name="locale.switch", requirements = {"locale"="^[a-z]{2}$"})
     */
    public function changeLocaleAction(Request $request) {

        $locale = $request->get('locale');

        $locale = in_array($locale, array_keys(self:: $locales)) ? $locale : $this->default_locale;

        $session = $request->getSession();
        $session->set('_locale', $locale);

        $referer = $request->server->get('HTTP_REFERER');

        // Handling dev environent when no referer was found.
        if($referer === null) {
            $script_name = $request->getScriptName();
            $referer = $request->getSchemeAndHttpHost();
            $referer .= preg_match('/dev/', $script_name) > 0 ? $script_name . '/' : '';
        }

        return $this->redirect($referer);
    }
}
