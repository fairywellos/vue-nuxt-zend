<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Conversation
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="etsy_tracking_listings")
 */
class EtsyTrackingListings
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="\Application\Entity\Listing")
     * @ORM\JoinColumn(name="listing_id", referencedColumnName="id", nullable=false, onDelete="CASCADE"))
     */
    protected $listing;

    /**
     * @ORM\Column(name="etsy_id",type="integer")
     */
    protected $etsyId;

    /**
     * @ORM\Column(name="url",type="string")
     */
    protected $url;

    /**
     * @ORM\Column(name="shop_id",type="integer")
     */
    protected $shopId;

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
    }

    /**
     * @return integer
     */
    public function getEtsyId()
    {
        return $this->etsyId;
    }

    /**
     * @param integer $etsyId
     */
    public function setEtsyId($etsyId): void
    {
        $this->etsyId = $etsyId;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return integer
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @param integer $shopId
     */
    public function setShopId($shopId): void
    {
        $this->shopId = $shopId;
    }
}