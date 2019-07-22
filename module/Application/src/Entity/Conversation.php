<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Conversation
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="conversation")
 * @ORM\Entity(repositoryClass="Application\Repository\ConversationRepository")
 */
class Conversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="date_added", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="date_modified", type="datetimetz",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateModified;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\User", mappedBy="conversations")
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Message", mappedBy="conversation", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="conversation_id", nullable=false)
     */
    protected $messages;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     */
    public function addUser($user): void
    {
        $this->users[] = $user;
        $user->addConversation($this);
    }

    /**
     * @param $user
     */
    public function removeUser($user): void
    {
        $this->users->removeElement($user);
    }

    /**
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Message $message
     */
    public function addMessage($message): void
    {
        $this->messages[] = $message;
        $message->setConversation($this);
    }

    /**
     * @param $message
     */
    public function removeMessage($message): void
    {
        $this->messages->removeElement($message);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $conversation = get_object_vars($this);

        unset($conversation['users']);
        unset($conversation['messages']);
        foreach ($this->getMessages() as $message) {
            $conversation['messages'][] = $message->toArray();
        }

        $conversation['users'] = array();
        foreach ($this->getUsers() as $user){
            $temp_user = array();
            $temp_user['user_id'] = $user->getId();
            $temp_user['publicName'] = $user->getPublicName();
            $temp_user['photo'] = $user->getPhotoUrl();

            $conversation['users'][] = $temp_user;
        }

        return $conversation;
    }
}