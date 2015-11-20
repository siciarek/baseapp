<?php

namespace Application\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;
use Application\MainBundle\Doctrine\DBAL\Types\CollectionElementTypeType;

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

    public function __toString() {
        return $this->getName() ? : '-';
    }
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
      * @ORM\Column(name="slug", length=128, unique=true)
      * @Gedmo\Slug(fields={"name"})
      */
     private $slug;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(name="name")
     */
    private $name;

    /**
     * @ORM\Column(name="info", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type = CollectionElementTypeType::UNKNOWN;

    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="elements", cascade={ "all" })
     */
    private $collection;

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
     * Set name
     *
     * @param string $name
     *
     * @return CollectionElement
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Set slug
     *
     * @param string $slug
     *
     * @return CollectionElement
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return CollectionElement
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
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
