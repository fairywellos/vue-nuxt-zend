<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Conversation
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="message_status")
 * @ORM\Entity(repositoryClass="Application\Repository\MessageStatusRepository")
 */
class MessageStatus
{
    CONST SEEN = 1;
    CONST NOT_SEEN = 2;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Message", inversedBy="messageStatuses")
     */
    protected $message;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(name="status",type="smallint")
     */
    protected $status;

    /**
     * @ORM\Column(name="date_added", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="date_modified", type="datetimetz",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateModified;

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
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $message_status = get_object_vars($this);

        $message_status['message'] = $this->getMessage()->getId();
        $message_status['user'] = $this->getUser()->getId();

        return $message_status;
    }
}