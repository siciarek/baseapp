<?php
namespace Application\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\MainBundle\Entity\Session
 *
 * @ORM\Entity
 * @ORM\Table(name="session")
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=128)
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $data;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $time;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $lifetime;
}
