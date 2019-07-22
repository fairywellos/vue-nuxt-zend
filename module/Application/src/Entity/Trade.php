<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation\ComposedObject;

/**
 * Class Trade
 * @package Application\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\TradeRepository")
 * @ORM\Table(name="trade",
 *     indexes={
 *      @ORM\Index(name="status_index",columns={"status"}),
 *      @ORM\Index(name="listing_id_index",columns={"listing_id"})
 *     }
 * )
 */
class Trade
{

    //Trade status type constants
    const PENDING = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;
    const UNAVAILABLE = 4;

    //Trade type constants
    const ITEM_TRADE = 1;
    const CASH_OFFER = 2;
    const MIXED_OFFER = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Listing", inversedBy="trades")
     * @ORM\JoinColumn(name="listing_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $listing;

    /**
     * @ORM\Column(name="status",type="smallint")
     */
    protected $status;

    /**
     * @ORM\Column(name="trade_type",type="smallint")
     */
    protected $tradeType;

    /**
     * @ORM\Column(name="notes", type="string", nullable=true)
     */
    protected $notes;


    /**
     * @ORM\Column(name="date_added",type="datetimetz", options={"default": "CURRENT_TIMESTAMP"} )
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="paid", type="boolean",options={"default":0})
     */
    protected $paid;


    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\TradeOffer", mappedBy="trade", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="trade_id", nullable=false)
     */
    protected $tradeOffers;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Rating", mappedBy="trade", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="trade_id")
     */
    protected $ratings;

    public function __construct()
    {
        $this->tradeOffers = new ArrayCollection();
        $this->ratings = new ArrayCollection();
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
     * @return Listing
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @param Listing $listing
     */
    public function setListing($listing): void
    {
        $this->listing = $listing;
        $listing->addTrade($this);
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return integer
     */
    public function getTradeType()
    {
        return $this->tradeType;
    }

    /**
     * @param integer $tradeType
     */
    public function setTradeType($tradeType): void
    {
        $this->tradeType = $tradeType;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes): void
    {
        $this->notes = $notes;
    }

    /**
     * @return boolean
     */
    public function getRated()
    {
        return $this->rated;
    }

    /**
     * @param boolean $rated
     */
    public function setRated($rated): void
    {
        $this->rated = $rated;
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
     * @return ArrayCollection
     */
    public function getTradeOffers()
    {
        return $this->tradeOffers;
    }

    /**
     * @param TradeOffer $tradeOffer
     */
    public function AddOffer($tradeOffer): void
    {
        $this->tradeOffers[] = $tradeOffer;
    }

    /**
     * @return integer
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param integer $paid
     */
    public function setPaid($paid): void
    {
        $this->paid = $paid;
    }

    /**
     * @return ArrayCollection
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param Rating $rating
     */
    public function addRating(Rating $rating): void
    {
        $this->ratings[] = $rating;
        $rating->setTrade($this);
    }

    public function toArray($full=false)
    {
        $array = get_object_vars($this);

        foreach ($array as $key => $value) {
            if (is_object($value)) {
                unset($array[$key]);
            }
        }
        $array["dateAdded"] = $this->getDateAdded();
        $tradeOffers = $this->getTradeOffers();

        if(!empty($tradeOffers)){
            $array["tradeOffers"] = [];
            foreach ($tradeOffers as $tradeOffer){
                $listing = null;
                if($tradeOffer->getListing()){
                    $listing = $tradeOffer->getListing()->toArray();
                }
                $array["tradeOffers"][$tradeOffer->getId()] = [
                    "id" => $tradeOffer->getId(),
                    "listing" => $listing,
                    "user" => $tradeOffer->getUser()->toArray(),
                    "cashValue" => $tradeOffer->getCashValue(),
                ];
            }
        }
        if($full){
            $array["listing"] = $this->getListing()->toArray();
        }

        return $array;
    }

}