<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Conversation
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="etsy_tracking_users")
 */
class EtsyTrackingUsers
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="\Application\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE"))
     */
    protected $user;

    /**
     * @ORM\Column(name="etsy_id",type="integer")
     */
    protected $etsyId;

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
     * @return integer
     */
    public function getEtsyId()
    {
        return $this->etsyId;
    }

    /**
     * @param int $etsyId
     */
    public function setEtsyId($etsyId): void
    {
        $this->etsyId = $etsyId;
    }
}