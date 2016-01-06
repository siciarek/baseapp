<?php

namespace Application\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Application\MainBundle\Entity\Message
 *
 * @ORM\Entity
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="MessageRepository")
 */
class Message
{
    use ORMBehaviors\Blameable\Blameable;
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\SoftDeletable\SoftDeletable;

    const TYPE_REGULAR = 'regular';
    const TYPE_REMINDER = 'reminder';
    
    const CHANNEL_EMAIL = 'email';
    const CHANNEL_SMS = 'sms';
    const CHANNEL_PHONE = 'phone';
    const CHANNEL_DASHBOARD = 'dashboard';
    const CHANNEL_CHAT = 'chat';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
   
    /**
     * @ORM\Column()
     */
    private $type = self::TYPE_REGULAR;

    /**
     * @ORM\Column()
     */
    private $channel = self::CHANNEL_EMAIL;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     */
    private $sender;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     */
    private $receiver;
    
    /**
     * @ORM\Column()
     */
    private $subject;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\Column()
     */
    private $description;

    /**
     * @ORM\Column()
     */
    private $info;    

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
     * Set type
     *
     * @param string $type
     * @return Message
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
     * Set channel
     *
     * @param string $channel
     * @return Message
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string 
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set data
     *
     * @param json $data
     * @return Message
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
     * Set description
     *
     * @param string $description
     * @return Message
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
     * @return Message
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
     * Set sender
     *
     * @param \Application\Sonata\UserBundle\Entity\User $sender
     * @return Message
     */
    public function setSender(\Application\Sonata\UserBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \Application\Sonata\UserBundle\Entity\User $receiver
     * @return Message
     */
    public function setReceiver(\Application\Sonata\UserBundle\Entity\User $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}
