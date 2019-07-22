<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rating
 * @package Application\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\RatingRepository")
 * @ORM\Table(name="rating",
 *     )
 *
 */
class Rating
{

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Trade", inversedBy="ratings")
     * @ORM\JoinColumn(name="trade_id", referencedColumnName="id",nullable=false)
     */
    protected $trade;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="ratings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\Column(name="rating", type="decimal",scale=2, precision=4, options={"default":0})
     */
    protected $rating;

    /**
     * @ORM\Column(name="review", type="text", nullable=true)
     */
    protected $review;

    /**
     * @ORM\Column(name="date_added", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="date_modified", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateModified;

    /**
     * @return Trade
     */
    public function getTrade()
    {
        return $this->trade;
    }

    /**
     * @param Trade $trade
     */
    public function setTrade($trade): void
    {
        $this->trade = $trade;
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
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     */
    public function setDateAdded($dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * @param \DateTime $dateModified
     */
    public function setDateModified($dateModified): void
    {
        $this->dateModified = $dateModified;
    }

    /**
     * @return string
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param string $review
     */
    public function setReview($review): void
    {
        $this->review = $review;
    }



}