<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Application\MainBundle\Entity\Session
 *
 * @ORM\Entity
 * @ORM\Table(name="page_group")
 * @ORM\Entity(repositoryClass="PageGroupRepository")
 */
class PageGroup
{

    use ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    public function __toString()
    {
        return $this->getName() ? : '-';
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
     * @ORM\Column()
     */
    private $role = 'ROLE_USER';

    /**
     * @ORM\Column(nullable=true)
     */
    private $icon = 'list-alt';

    /**
     * @ORM\OrderBy({"enabled" = "DESC", "name" = "ASC"})
     * @ORM\OneToMany(targetEntity="Page", mappedBy="group", cascade={ "all" }, orphanRemoval=true)
     */
    private $pages;

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->translate()->getName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PageGroup
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
     * Set role
     *
     * @param string $role
     * @return PageGroup
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
     * Set icon
     *
     * @param string $icon
     * @return PageGroup
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Add pages
     *
     * @param \Application\MainBundle\Entity\Page $pages
     * @return PageGroup
     */
    public function addPage(\Application\MainBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Application\MainBundle\Entity\Page $pages
     */
    public function removePage(\Application\MainBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }

}
