<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Conversation
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="message")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Conversation", inversedBy="messages")
     * @ORM\JoinColumn(name="conversation_id", referencedColumnName="id", nullable=false)
     */
    protected $conversation;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @ORM\Column(name="date_added", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="date_modified", type="datetimetz",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateModified;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\MessageStatus", mappedBy="message", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="message_id", nullable=false)
     */
    protected $messageStatuses;

    public function __construct()
    {
        $this->messageStatuses = new ArrayCollection();
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param mixed $dateAdded
     */
    public function setDateAdded($dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return mixed
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * @param mixed $dateModified
     */
    public function setDateModified($dateModified): void
    {
        $this->dateModified = $dateModified;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return Conversation
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * @param $conversation
     */
    public function setConversation($conversation): void
    {
        $this->conversation = $conversation;
    }

    /**
     * @return ArrayCollection
     */
    public function getMessageStatuses()
    {
        return $this->messageStatuses;
    }

    /**
     * @param MessageStatus $messageStatus
     */
    public function addMessageStatus($messageStatus): void
    {
        $this->messageStatuses[] = $messageStatus;
        $messageStatus->setMessage($this);
    }

    /**
     * @param MessageStatus $messageStatus
     */
    public function removeMessageStatus($messageStatus): void
    {
        $this->messageStatuses->removeElement($messageStatus);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $message = get_object_vars($this);

        unset($message['user']);
        $message['user']['user_id'] = $this->getUser()->getId();
        $message['user']['publicName'] = $this->getUser()->getPublicName();
        $message['user']['photo'] = $this->getUser()->getPhotoUrl();
        $message['conversation'] = $this->getConversation()->getId();

        unset($message['messageStatuses']);
        foreach ($this->getMessageStatuses() as $message_status){
            $message['messageStatuses'][] = $message_status->toArray();
        }

        return $message;
    }
}