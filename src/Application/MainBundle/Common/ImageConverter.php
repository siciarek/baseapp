<?php

namespace Application\MainBundle\Common;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Process\ProcessBuilder;
use Application\MainBundle\Common\XProcess;

class ImageConverter implements ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->setContainer($container);
    }

    public function convert($imageUrl, $targetFilename, $targetFormat = 'png') {

        $export = sprintf('--export-%s=%s', $targetFormat, $targetFilename);
        
        $process = new XProcess($this->getContainer(), $this->getContainer()->getParameter('exec.inkscape'));
        
        $app = $process->getApplication();
        
        $builder = new ProcessBuilder();
        $builder->setPrefix($app);
        $arguments = [
            '--export-background=white',
            '--without-gui',
            $imageUrl,
            $export
        ];
        
        $cmd = $builder
                ->setArguments($arguments)
                ->getProcess()
                ->getCommandLine()
                ;
        
        return $process->run($cmd);
    }

    public function remoteConvert($imageUrl, $targetFilename, $targetFormat = 'png') {

        // Pobierz formularz ze strony:
        $url = 'http://image.online-convert.com/convert-to-' . $targetFormat;
        $curl = $this->getContainer()->get('app.common.utils.curl');
        $resp = $curl->GET($url);
        $content = $resp['response'];
        $crawler = new Crawler($content);

        $inputs = $crawler->filter('form#forms *[name]');

        $data = [];

        foreach ($inputs as $input) {
            $type = $input->getAttribute('type');
            $key = $input->getAttribute('name');
            $value = $input->getAttribute('value');
            $checked = $input->getAttribute('checked');

            if ($type === 'radio' and empty($checked) or empty($key)) {
                continue;
            }

            if ($type === 'checkbox' and empty($checked)) {
                continue;
            }

            $data[$key] = $value;
        }

        // Wypełnij formularz danymi:
        $select = $crawler->filter('form#forms select option[selected]')->first();

        if ($select->getNode(0) !== null) {
            $data['quality'] = $select->getNode(0)->getAttribute('value');
        }

        $data['external_url'] = $imageUrl;

        // Wyślij formularz:
        $url = 'http://www26.online-convert.com/init-image-conversion';
        $resp = $curl->POST($url, $data);
        $content = $resp['response'];

        // Pobierz iframe z referencją do pobieranego pliku:
        $crawler = new Crawler($content);
        $url = $crawler->filter('iframe#download_try')->first()->getNode(0)->getAttribute('src');

        $interval = 15;

        // Zgodnie z zapisem na stronie iframe odświeżaj zawartość:
        do {
            sleep($interval);

            $resp = $curl->GET($url);
            $content = $resp['response'];

            $crawler = new Crawler($content);
            $iframe = $crawler->filter('iframe#download_file')->first()->getNode(0);

            if ($iframe !== null) {
                $url = $iframe->getAttribute('src');
                break;
            }

            $message = strip_tags($crawler->filter('#download_box')->first()->html());
            $title = $crawler->filter('#download_box h3')->first()->getNode(0)->nodeValue;
            $title = trim($title);
            $message = str_replace($title, '', $message);
            $message = trim($message);

            if ($title === 'Error message') {
                throw new \Exception($message);
            }
        } while ($iframe == null);

        $resp = $curl->GET($url);
        $content = $resp['response'];
        $temp = explode('=', $resp['headers']['content-disposition']);
        $filename = array_pop($temp);
        $filename = json_decode($filename);

        file_put_contents($targetFilename, $content);
    }
    
    /**
     * Gets the container
     *
     * @return ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
}
