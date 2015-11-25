<?php

namespace Application\MainBundle\Twig\Extension;                                                                                                                        
                                                                                                                                                                        
use EWZ\Bundle\TextBundle\Templating\Helper\TextHelper;                                                                                                                 
use Symfony\Component\DependencyInjection\Container;                                                                                                                    
use Symfony\Component\DependencyInjection\ContainerAwareInterface;                                                                                                      
use Symfony\Component\Templating\Helper\Helper;                                                                                                                         
use Symfony\Component\DependencyInjection\ContainerInterface;                                                                                                           
use Symfony\Component\HttpKernel\KernelInterface;                                                                                                                       
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;                                                                                                                  
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;                                                                                                          
                                                                                                                                                                        
                                                                                                                                                                        
class TwigExtension extends \Twig_Extension                                                                                                                  
{                                                                                                                                                                       
    protected $loader;                                                                                                                                                  
    protected $generator;                                                                                                                                               
    protected $textHelper;                                                                                                                                              
    protected $container;                                                                                                                                               
                                                                                                                                                                        
    public function __construct(FilesystemLoader $loader, UrlGeneratorInterface $generator, TextHelper $textHelper, Container $container)                               
    {
        $this->loader = $loader;
        $this->generator = $generator;
        $this->textHelper = $textHelper;
        $this->container = $container;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'application_main_twig_extension';
    }

    /**
     * Filters declaration
     */
    public function getFilters()
    {
        return [
//            'highlight' => new \Twig_SimpleFunction($this, 'highlight', array('is_safe' => array('html'))),
//            'truncate'  => new \Twig_SimpleFunction($this, 'truncate', array('is_safe' => array('html'))),
//            'excerpt'   => new \Twig_SimpleFunction($this, 'excerpt', array('is_safe' => array('html'))),
//            'wrap'      => new \Twig_SimpleFunction($this, 'wrap', array('is_safe' => array('html'))),
        ];
    }

    /**
     * Functions declaration
     */
    public function getFunctions()
    {
        return [
//            'mailto' => new \Twig_SimpleFunction($this, 'mailto', array('is_safe' => array('html'))),
        ];
    }

    /**
     * Human readable file size
     *
     * @param $fileSizeInBytes
     * @return string
     */
    public function hrfs($fileSizeInBytes)
    {
        $i = 0;
        $byteUnits = ['B', ' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];

        do {
            $fileSizeInBytes = $fileSizeInBytes / 1024;
            $i++;
        } while ($fileSizeInBytes > 1024);

        return sprintf('%0.1f%s', floatval(max(array($fileSizeInBytes, 0.1))), $byteUnits[$i]);
    }

    public function truncate($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false)
    {
        return $this->textHelper->truncate($text, $length, $truncate_string, $truncate_lastspace);
    }

    public function highlight($text, $phrase, $highlighter = '<strong class="highlight">\\1</strong>')
    {
        return $this->textHelper->highlight($text, $phrase, $highlighter);
    }

    public function excerpt($text, $phrase, $radius = 100, $excerpt_string = '...', $excerpt_space = false)
    {
        return $this->textHelper->excerpt($text, $phrase, $radius, $excerpt_string, $excerpt_space);
    }

    public function wrap($text, $line_width = 80)
    {
        return $this->textHelper->wrap($text, $line_width);
    }

    public function mailto($email)
    {
        return sprintf('<a title="%s" href="mailto:%s">%s</a>', $email, $email, $email);
    }
}
