<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\MainBundle\Entity\Country
 *
 * @ORM\Entity
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="CountryRepository")
 */
class Country {

    public function __toString() {
        return $this->getCode();
    }

    /**
     * @ORM\Id
     * @ORM\Column(length=2, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(length=5)
     */
    private $language;

    /**
     * @ORM\Column(length=3)
     */
    private $currency;

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
     * @ORM\OneToMany(targetEntity="AdministrativeDivision", mappedBy="country")
     */
    private $administrativeDivisions;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->administrativeDivisions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Country
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Country
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Country
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
     * @return Country
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
     * @return Country
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
     * @return Country
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
     * Add administrativeDivision
     *
     * @param \Application\MainBundle\Entity\AdministrativeDivision $administrativeDivision
     *
     * @return Country
     */
    public function addAdministrativeDivision(\Application\MainBundle\Entity\AdministrativeDivision $administrativeDivision)
    {
        $this->administrativeDivisions[] = $administrativeDivision;

        return $this;
    }

    /**
     * Remove administrativeDivision
     *
     * @param \Application\MainBundle\Entity\AdministrativeDivision $administrativeDivision
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAdministrativeDivision(\Application\MainBundle\Entity\AdministrativeDivision $administrativeDivision)
    {
        return $this->administrativeDivisions->removeElement($administrativeDivision);
    }

    /**
     * Get administrativeDivisions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdministrativeDivisions()
    {
        return $this->administrativeDivisions;
    }
}
