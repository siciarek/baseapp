<?php

namespace Application\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Application\MainBundle\Entity\PageGroupTranslation
 *
 * @ORM\Entity
 */
class PageGroupTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @ORM\Column(length=255)
     */
    private $name;

    /**
     * Set name
     *
     * @param string $name
     * @return PageGroupTranslation
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
}
