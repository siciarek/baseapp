<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Application\MainBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Application\MainBundle\Entity as E;

class LoadCollectionData extends BasicFixture {

    /**
     * @var numeric 
     */
    protected $order = 3;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        
        $c = new E\Collection();
        $c->setName('Pierwsza');
        $c->setInfo('Krótka informacja na temat pierwszej kolekcji.');
        
        foreach(range(1, 10) as $i) {
            $e = new E\CollectionElement();
            $e->setName('Element nr ' . $i);
            $e->setInfo('Opis elementu nr ' . $i);
            $e->setCollection($c);
            
            $c->addElement($e);
        }

        $manager->persist($c);
        
        $manager->flush();
    }
}
