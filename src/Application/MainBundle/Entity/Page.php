<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Application\MainBundle\Entity\Page
 *
 * @ORM\Entity
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="PageRepository")
 * @ORM\NamedQueries({
 *   @ORM\NamedQuery(name="count", query="SELECT COUNT(o) FROM __CLASS__ o"),
 *   @ORM\NamedQuery(name="sorted", query="SELECT o FROM __CLASS__ o WHERE o.enabled = true ORDER BY o.position DESC")
 * })
 */
class Page
{

    use ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    public function __toString()
    {
        return $this->getTitle()? : '-';
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="integer")
     */
    private $position = 1;
    
    /**
     * @ORM\Column()
     */
    private $name;

    /**
     * @ORM\Column(name="slug", length=128, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="PageGroup", inversedBy="pages", cascade={ "all" })
     */
    private $group;

    public function getTitle() {
        return $this->translate()->getTitle();
    }
    
    public function getContent() {
        return $this->translate()->getContent();
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
     * Set position
     *
     * @param integer $position
     * @return Page
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Page
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
     * Set slug
     *
     * @param string $slug
     * @return Page
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
     * @return Page
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
     * Set group
     *
     * @param \Application\MainBundle\Entity\PageGroup $group
     * @return Page
     */
    public function setGroup(\Application\MainBundle\Entity\PageGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Application\MainBundle\Entity\PageGroup 
     */
    public function getGroup()
    {
        return $this->group;
    }
}
