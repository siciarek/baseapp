<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\MainBundle\Entity\Place
 *
 * @ORM\Entity
 * @ORM\Table(name="place")
 * @ORM\Entity(repositoryClass="PlaceRepository")
 */
class Place {

    public function __toString() {
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
     * @ORM\ManyToOne(targetEntity="AdministrativeDivision", inversedBy="places")
     */
    private $administrativeDivision;

    /**
     * Set id
     *
     * @param int $id
     *
     * @return Place
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
     * @return Place
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
     * @return Place
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
     * @return Place
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
     * @return Place
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
     * Set administrativeDivision
     *
     * @param \Application\MainBundle\Entity\AdministrativeDivision $administrativeDivision
     *
     * @return Place
     */
    public function setAdministrativeDivision(\Application\MainBundle\Entity\AdministrativeDivision $administrativeDivision = null)
    {
        $this->administrativeDivision = $administrativeDivision;

        return $this;
    }

    /**
     * Get administrativeDivision
     *
     * @return \Application\MainBundle\Entity\AdministrativeDivision
     */
    public function getAdministrativeDivision()
    {
        return $this->administrativeDivision;
    }
}
