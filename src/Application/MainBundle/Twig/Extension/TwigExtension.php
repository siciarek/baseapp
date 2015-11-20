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
        return array(
            'hrfs'  => new \Twig_Filter_Method($this, 'hrfs'),
            'get_class' => new \Twig_Filter_Function('get_class'),
            'print_r'   => new \Twig_Filter_Function('print_r'),
            'ucfirst'   => new \Twig_Filter_Function('ucfirst'),
            'var_dump'  => new \Twig_SimpleFilter('var_dump', function (\Twig_Environment $env, $var) {
                ob_start();
                var_dump($var);
                return ob_get_clean();
            }, array('needs_environment' => true, 'is_safe' => array('html'))),

            'highlight' => new \Twig_Filter_Method($this, 'highlight', array('is_safe' => array('html'))),
            'truncate'  => new \Twig_Filter_Method($this, 'truncate', array('is_safe' => array('html'))),
            'excerpt'   => new \Twig_Filter_Method($this, 'excerpt', array('is_safe' => array('html'))),
            'wrap'      => new \Twig_Filter_Method($this, 'wrap', array('is_safe' => array('html'))),
            'pre'       => new \Twig_Filter_Method($this, 'pre', array('is_safe' => array('html'))),
        );
    }

    /**
     * Functions declaration
     */
    public function getFunctions()
    {
        return array(
            'mailto' => new \Twig_Function_Method($this, 'mailto', array('is_safe' => array('html'))),
            'pre'    => new \Twig_Function_Method($this, 'pre', array('is_safe' => array('html'))),
        );
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

    public function mailto($email)
    {
        return sprintf('<a title="%s" href="mailto:%s">%s</a>', $email, $email, $email);
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

    public function pre($string)
    {

        $output[] = '<pre>';
        $output[] = $string;
        $output[] = '</pre>';

        return implode("\n", $output);
    }
}
