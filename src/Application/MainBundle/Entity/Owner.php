<?php

namespace Application\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Application\MainBundle\Entity\Collection
 *
 * @ORM\Entity
 * @ORM\Table(name="owner")
 * @ORM\Entity(repositoryClass="OwnerRepository")
 */
class Owner
{
    use ORMBehaviors\Blameable\Blameable;
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\SoftDeletable\SoftDeletable;

    public function __toString() {
        return $this->getFullName() ? : '-';
    }

    /**
     * Returns owner's fullname
     * 
     * @return type
     */
    public function getFullName() {
        $temp = [];
        $temp[] = $this->firstName;
        $temp[] = $this->lastName;
        
        $temp = array_filter($temp, function($e){
            $tmp = trim($e);
            return strlen($tmp) > 0;
        });
        
        $fullName = implode(' ', $temp);
        
        return $fullName ? : null;
    }
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="slug", length=128, unique=true)
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     */
    private $slug;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(name="first_name")
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name")
     */
    private $lastName;

    /**
     * @ORM\Column(name="info", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="CollectionElement", inversedBy="owners", cascade={ "all" })
     */
    private $elements;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elements = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Owner
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Owner
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Owner
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Owner
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Owner
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
     * Set description
     *
     * @param string $description
     *
     * @return Owner
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
     * Add element
     *
     * @param \Application\MainBundle\Entity\CollectionElement $element
     *
     * @return Owner
     */
    public function addElement(\Application\MainBundle\Entity\CollectionElement $element)
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * Remove element
     *
     * @param \Application\MainBundle\Entity\CollectionElement $element
     */
    public function removeElement(\Application\MainBundle\Entity\CollectionElement $element)
    {
        $this->elements->removeElement($element);
    }

    /**
     * Get elements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElements()
    {
        return $this->elements;
    }
}
