<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        date_default_timezone_set('Europe/Warsaw');
        parent::__construct($environment, $debug);
    }

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Siciarek\ChatBundle\SiciarekChatBundle(),             
            new Knp\Bundle\TimeBundle\KnpTimeBundle(),
            
            new Pix\SortableBehaviorBundle\PixSortableBehaviorBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sonata\ClassificationBundle\SonataClassificationBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            
//            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
//            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
//            new JMS\AopBundle\JMSAopBundle(),
//            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
//            new EWZ\Bundle\TextBundle\EWZTextBundle(), // Temporarily
//            new Sonata\MediaBundle\SonataMediaBundle(),
//            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),

            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Nelmio\SecurityBundle\NelmioSecurityBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Siciarek\JsTransBundle\SiciarekJsTransBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),

            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            new Application\Sonata\ClassificationBundle\ApplicationSonataClassificationBundle(),
            new Application\MainBundle\ApplicationMainBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle();
            $bundles[] = new Bazinga\Bundle\FakerBundle\BazingaFakerBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
