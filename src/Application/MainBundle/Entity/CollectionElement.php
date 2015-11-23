<?php

namespace Application\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Application\MainBundle\Entity\CollectionElement
 *
 * @ORM\Entity
 * @ORM\Table(name="collection_element")
 * @ORM\Entity(repositoryClass="CollectionElementRepository")
 */
class CollectionElement
{
    use ORMBehaviors\Blameable\Blameable;
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type = 'unknown'; // [ "unknown", "basic", "enhanced", "advanced" ]

    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="elements", cascade={ "all" })
     */
    private $collection;

    public function __toString() {
        return $this->getName() ? : '-';
    }
    
    public function getName() {
        return $this->translate()->getName();
    }
    
    public function getInfo() {
        return $this->translate()->getInfo();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set collection
     *
     * @param \Application\MainBundle\Entity\Collection $collection
     *
     * @return CollectionElement
     */
    public function setCollection(\Application\MainBundle\Entity\Collection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return \Application\MainBundle\Entity\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return CollectionElement
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return CollectionElement
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
