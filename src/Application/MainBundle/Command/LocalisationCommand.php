<?php

namespace Application\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use utilphp\util;
use Application\MainBundle\Common\Utils\Vcard;
use Application\MainBundle\Entity as E;

class LocalisationCommand extends ContainerAwareCommand {

    /**
     * @source http://ksng.gugik.gov.pl/urzedowe_nazwy_miejscowosci.php
     */
    protected function configure() {
        $this
                ->setName('loc')
                ->setDescription('Get Poland localisation list.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $filename = __DIR__ . '/../Resources/data/places.csv';

        $adr = $em->getRepository('ApplicationMainBundle:AdminstrativeDivision');
        
        $handle = fopen("$filename", "r");
        $headers = [];
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if($headers === []) {
                $headers = $data;
                $output->writeln(json_encode($headers));
                continue;
            }

            $dat = [
                'województwo' => $data[4],
                'powiat' => $data[3],
                'gmina' => $data[2],
            ];
            
            $adw = new E\AdministrativeDivision();
            $adw->setDescription('województwo');
            $adw->setName($data[4]);
            $em->persist($adw);
            $em->flush();
            
            $adp = new E\AdministrativeDivision();
            $adp->setDescription('powiat');
            $adp->setName($data[3]);
            $em->persist($adp);
            $em->flush();

            $adg = new E\AdministrativeDivision();
            $adg->setDescription('gmina');
            $adg->setName($data[2]);
            $em->persist($adg);
            $em->flush();
            
            $place = new E\Place();
            $place->setId($data[5]);
            $place->setName($data[0]);
            $place->setDescription($data[1]);
            $place->setData($dat);
            
            $em->persist($place);
            $em->flush();
            
            $output->writeln(json_encode([$place->getId(), $place->getName()]));
        }

        fclose($handle);
        return;

        $filename = __DIR__ . '/../Resources/data/places.xlsx';


        $output->writeln('START READING');

        $xls = $this->getContainer()->get('phpexcel')->createPHPExcelObject($filename);

        $output->writeln('END READING');

        $output->writeln('START FETCHING DATA');

        $data = $xls->getSheet()->toArray(null, false, false, false);

        $output->writeln('END FETCHING DATA');

        ldd($data);
    }

}
