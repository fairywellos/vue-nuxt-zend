<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Notification
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="Application\Repository\NotificationRepository")
 */
class Notification implements \JsonSerializable
{
    CONST SEEN = 1;
    CONST NOT_SEEN = 2;

    CONST TYPE_NEW_OFFER = 1;
    CONST TYPE_TRADE_ACCEPTED = 2;
    CONST TYPE_TRADE_REJECTED = 3;
    CONST TYPE_SEARCH = 4;
    CONST TYPE_TRADER_NEW_LISTING = 5;
    CONST TYPE_WATCHLIST_LISTING_UPDATED = 6;
    CONST TYPE_WATCHLIST_LISTING_NOT_AVAILABLE = 7;
    CONST TYPE_WATCHLIST_LISTING_REPOSTED = 8;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="title",type="string")
     */
    protected $title;

    /**
     * @ORM\Column(name="description",type="string")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\Column(name="type",type="smallint")
     */
    protected $type;

    /**
     * @ORM\Column(name="status",type="smallint")
     */
    protected $status;

    /**
     * @ORM\Column(name="link",type="string")
     */
    protected $link;

    /**
     * @ORM\Column(name="photo",type="string", nullable=true)
     */
    protected $photo;

    /**
     * @ORM\Column(name="date_added", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="date_modified", type="datetimetz",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateModified;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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
        $user->addNotification($this);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type): void
    {
        $this->type = $type;
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
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
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
     * @return array
     */
    public function toArray()
    {
        $notification = get_object_vars($this);
        unset($notification['user']);
        unset($notification['dateModified']);
        $notification['dateAdded'] = $notification['dateAdded']->format('Y-m-d H:i:s');

        return $notification;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}