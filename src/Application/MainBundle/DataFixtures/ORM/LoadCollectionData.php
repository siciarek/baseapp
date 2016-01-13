<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Application\MainBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Application\MainBundle\Entity as E;

class LoadCollectionData extends BasicFixture {

    /**
     * @var numeric 
     */
    protected $order = 4;
    protected $limit = 50;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $faker = \Faker\Factory::create('pl_PL');

        $owners = [];

        foreach (['Pierwsza', 'Druga', 'Trzecia'] as $cname) {
            $cinfo = 'Krótka informacja na temat kolekcji "' . $cname . '".';
            
            $c = new E\Collection();
            $c->setName($cname);
            $c->setInfo($cinfo);


            foreach (range(1, rand(floor($this->limit / 2), $this->limit)) as $i) {

                $owc = rand(1, 5);
                $tempOwners = [];
                for($i = 0; $i < $owc; $i++) {

                    do {
                        $firstName = $faker->firstName;
                        $lastName = $faker->lastName;
                    } while(array_key_exists($firstName.$lastName, $owners));

                    $o = new E\Owner();
                    $o->setFirstName($firstName);
                    $o->setLastName($lastName);
                    $o->setInfo($faker->sentence(10));

                    $manager->persist($o);

                    $tempOwners[] = $o;
                }

                $name = preg_replace('/\.$/', '', $faker->sentence(1));
                $info = $faker->sentences(1, true);

                $e = new E\CollectionElement();
                $e->translate('pl')->setName($name);
                $e->translate('pl')->setInfo($info);
                $e->mergeNewTranslations();
                $e->setCollection($c);

                foreach($tempOwners as $to) {
                    $e->addOwner($to);
                }

                $c->addElement($e);
            }

            $manager->persist($c);

        }

        $manager->flush();
    }

}
