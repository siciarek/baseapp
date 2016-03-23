<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Application\MainBundle\Entity\AdministrativeDivision
 *
 * @ORM\Entity
 * @ORM\Table(name="administrative_division")
 * @ORM\Entity(repositoryClass="AdministrativeDivisionRepository")
 */
class AdministrativeDivision implements \Knp\DoctrineBehaviors\Model\Tree\NodeInterface
{

    use ORMBehaviors\Tree\Node;

    public function __toString()
    {
        return $this->getName()? : '-';
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column()
     */
    private $name;

    /**
     * @ORM\Column(nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\OrderBy({"name" = "ASC"})
     * @ORM\OneToMany(targetEntity="Place", mappedBy="administrativeDivision")
     */
    private $places;

    /**
     * Set id
     *
     * @param int $id
     *
     * @return AdministrativeDivision
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return int
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
     * @return AdministrativeDivision
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
     * Set description
     *
     * @param string $description
     *
     * @return AdministrativeDivision
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return AdministrativeDivision
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
     * Set data
     *
     * @param json $data
     *
     * @return AdministrativeDivision
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return json
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->places = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add place
     *
     * @param \Application\MainBundle\Entity\Place $place
     *
     * @return AdministrativeDivision
     */
    public function addPlace(\Application\MainBundle\Entity\Place $place)
    {
        $this->places[] = $place;

        return $this;
    }

    /**
     * Remove place
     *
     * @param \Application\MainBundle\Entity\Place $place
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlace(\Application\MainBundle\Entity\Place $place)
    {
        return $this->places->removeElement($place);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaces()
    {
        return $this->places;
    }
}
