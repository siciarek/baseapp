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
 *   @ORM\NamedQuery(name="sorted", query="SELECT o FROM __CLASS__ o WHERE o.enabled = true  BY o.position DESC")
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
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $displayTitle = true;

    /**
     * @ORM\Column()
     */
    private $name;

    /**
     * @Gedmo\Slug(
     * handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="group"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * },
     * fields={"name"}, suffix=".html")
     * @ORM\Column(name="slug", length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="PageGroup", inversedBy="pages", cascade={ "all" })
     */
    private $group;

    /**
     * @ORM\Column()
     */
    private $role = 'ROLE_USER';

    public function getTitle()
    {
        return $this->translate()->getTitle();
    }

    public function getContent()
    {
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
     * Set displayTitle
     *
     * @param boolean $displayTitle
     * @return Page
     */
    public function setDisplayTitle($displayTitle)
    {
        $this->displayTitle = $displayTitle;

        return $this;
    }

    /**
     * Get displayTitle
     *
     * @return boolean 
     */
    public function getDisplayTitle()
    {
        return $this->displayTitle;
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
     * Set role
     *
     * @param string $role
     * @return Page
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
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
}
