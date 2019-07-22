<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TradeOffer
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="trade_offer",
 *     indexes={@ORM\Index(name="trade_id_index", columns={"trade_id"}),
 *              @ORM\Index(name="listing_id_index",columns={"listing_id"}),
 *              @ORM\Index(name="user_id_index",columns={"user_id"})
 *      }
 * )
 */
class TradeOffer
{
    /**
     *  @ORM\Id
     *  @ORM\GeneratedValue
     *  @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     *  @ORM\ManyToOne(targetEntity="\Application\Entity\Trade", inversedBy="tradeOffers")
     *  @ORM\JoinColumn(name="trade_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $trade;

    /**
     *  @ORM\ManyToOne(targetEntity="\Application\Entity\Listing", inversedBy="tradeOffers")
     *  @ORM\JoinColumn(name="listing_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $listing;

    /**
     *  @ORM\ManyToOne(targetEntity="\Application\Entity\User")
     *  @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     *  @ORM\Column(name="cash_value", type="decimal", scale=2)
     */
    protected $cashValue;

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
        $trade->AddOffer($this);
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
        if($listing){
            $listing->addTradeOffer($this);
        }
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
    public function getCashValue()
    {
        return $this->cashValue;
    }

    /**
     * @param float $cashValue
     */
    public function setCashValue($cashValue): void
    {
        $this->cashValue = $cashValue;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}