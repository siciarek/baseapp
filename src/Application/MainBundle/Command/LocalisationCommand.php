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

class LocalisationCommand extends ContainerAwareCommand
{

    const STEP = 5000;

    /**
     * @source: http://ksng.gugik.gov.pl/urzedowe_nazwy_miejscowosci.php
     * @geo: http://cybermoon.pl/wiedza/wspolrzedne/wspolrzedne_polskich_miejscowosci_a.html
     */
    protected function configure()
    {
        $this
                ->setName('loc')
                ->setDescription('Create localisation list of Poland administrative divisions and places.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $filename = __DIR__ . '/../Resources/data/places.csv';

        $adr = $em->getRepository('ApplicationMainBundle:AdministrativeDivision');
        $plr = $em->getRepository('ApplicationMainBundle:Place');
        $cr = $em->getRepository('ApplicationMainBundle:Country');

        $handle = fopen("$filename", "r");
        $headers = [];

        $counter = 0;
        $adx = 1;

        $temp = $cr->findByCode('PL');
        $country = new E\Country();

        if (count($temp) > 0) {
            $country = $temp[0];
        }

        $country->setCode('PL');
        $country->setName('Poland');
        $country->setLanguage('PL_pl');
        $country->setCurrency('PLN');
        $em->persist($country);
        $em->flush();
        $em->refresh($country);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            if ($headers === []) {
                $headers = $data;
                $output->writeln(json_encode($headers));
                continue;
            }

            $data = array_map('trim', $data);

            $dat = [
                'województwo' => $data[4],
                'powiat' => $data[3],
                'gmina' => $data[2],
            ];

            $wo = $adr->findBy([
                'description' => 'województwo',
                'name' => $data[4],
            ]);

            $adw = new E\AdministrativeDivision();

            if (count($wo) > 0) {
                $adw = $wo[0];
            } else {
                $adw->setId($adx++);
            }
            $adw->setCountry($country);

            $adw->setDescription('województwo');
            $adw->setName($data[4]);
            $em->persist($adw);

            $po = $adr->findBy([
                'description' => 'powiat',
                'name' => $data[3],
            ]);

            $adp = new E\AdministrativeDivision();

            if (count($po) > 0) {
                $adp = $po[0];
            } else {
                $adp->setId($adx++);
            }
            $adp->setCountry($country);

            $adp->setDescription('powiat');
            $adp->setName($data[3]);
            $adp->setChildNodeOf($adw);
            $em->persist($adp);
            $em->persist($adw);

            $gm = $adr->findBy([
                'description' => 'gmina',
                'name' => $data[2],
            ]);

            $adg = new E\AdministrativeDivision();

            if (count($gm) > 0) {
                $adg = $gm[0];
            } else {
                $adg->setId($adx++);
            }
            $adg->setCountry($country);

            $adg->setDescription('gmina');
            $adg->setName($data[2]);

            $adg->setChildNodeOf($adp);
            $em->persist($adg);
            $em->persist($adp);

            $place = new E\Place();

            $pl = $adr->findBy([
                'id' => $data[5],
                'name' => $data[0],
            ]);

            if (count($pl) === 0) {
                $place->setAdministrativeDivision($adp);
                $place->setId($data[5]);
                $place->setName($data[0]);
                $place->setDescription($data[1]);
                $place->setData($dat);

                $em->persist($place);

                $counter++;
            }

            if ($counter >= self::STEP) {
                $em->flush();
                $counter = 0;
                $output->write('.');
            }
        }

        fclose($handle);
    }
}
