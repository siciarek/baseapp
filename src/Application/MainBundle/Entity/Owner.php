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
    const TYPE_PERSON = 'person';
    const TYPE_ORGANISATION = 'organisation';
    
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
        $temp[] = $this->getName();
        $temp[] = $this->getLastName();        
        
        $temp = array_filter(function($e){
            $tmp = trim($e);
            return strlen($tmp) > 0;
        }, $temp);
        
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
     * @Gedmo\Slug(fields={"name", "lastName"})
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
     * @ORM\Column(name="last_name")
     */
    private $lastName;

    /**
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type = self::TYPE_PERSON;

    /**
     * @ORM\Column(name="info", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
     * Set name
     *
     * @param string $name
     *
     * @return Owner
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
     * Set type
     *
     * @param string $type
     *
     * @return Owner
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
}
