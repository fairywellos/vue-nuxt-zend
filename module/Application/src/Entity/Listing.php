<?php

namespace Application\Entity;

use Application\Service\UploadManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Listing
 * @package Application\Entity
 * @ORM\ Entity
 * @ORM\Table(name="listing",
 *     indexes={@ORM\Index(name="trade_or_cash_index", columns={"trade_or_cash"}),
 *              @ORM\Index(name="listing_type_index",columns={"listing_type"}),
 *              @ORM\Index(name="status_index",columns={"status"}),
 *              @ORM\Index(name="main_category_id_index",columns={"main_category_id"}),
 *              @ORM\Index(name="user_id_index",columns={"user_id"}),
 *              @ORM\Index(name="shipping_index",columns={"shipping"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="Application\Repository\ListingRepository")
 */
class Listing
{

    //Listing type constants
    const TYPE_GENERAL = 1;
    const TYPE_ITEM = 2;
    const TYPE_SERVICE = 3;
    const TYPE_DEAL = 4;

    //Listing condition constants
    const USED = 1;
    const NEW = 2;

    //Listing trade type constants
    const TRADE_OFFER = 1;
    CONST CASH_OFFER = 2;
    CONST TRADE_AND_CASH_OFFER = 3;

    //Listing shipping status constants
    const SHIPPING = 1;
    const NOT_SHIPPING = 0;

    //Listing status constants
    const ACTIVE = 1;
    const INACTIVE = 2;
    const TRADED = 3;

    //Local trades constants
    const LOCAL_TRADE = 1;
    const GLOBAL_TRADE = 0;

    //Saved process status
    const REMOVE_SAVED = 0;
    const ADD_SAVED = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\MainCategory", inversedBy="listings")
     * @ORM\JoinColumn(name="main_category_id", referencedColumnName="id", nullable=false)
     */
    protected $mainCategory;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="listings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\Column(name="listing_type", type="smallint")
     */
    protected $listingType;

    /**
     * @ORM\Column(name="status", type="smallint")
     */
    protected $status;

    /**
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @ORM\Column(name="description", nullable=true, type="text")
     */
    protected $description;

    /**
     * @ORM\Column(name="price",type="decimal",scale=2)
     */
    protected $price;

    /**
     * @ORM\Column(name="qty",type="smallint")
     */
    protected $qty;

    /**
     * @ORM\Column(name="listing_condition",type="smallint")
     */
    protected $condition;

    /**
     * @ORM\Column(name="trade_or_cash", type="smallint")
     */
    protected $tradeOrCash;

    /**
     * @ORM\Column(name="shipping", type="boolean")
     */
    protected $shipping;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id",nullable=true)
     */
    protected $location;

    /**
     * @ORM\Column(name="brand", nullable=true)
     */
    protected $brand;

    /**
     * @ORM\Column(name="color", nullable=true)
     */
    protected $color;

    /**
     * @ORM\Column(name="material", nullable=true)
     */
    protected $material;

    /**
     * @ORM\Column(name="date_added", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="date_modified", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateModified;

    /**
     * @ORM\Column(name="availability",type="datetimetz")
     */
    protected $availability;

    /**
     * @ORM\Column(name="local_trades_only",type="boolean")
     */
    protected $localTradesOnly;

    /**
     * @ORM\Column(name="year", type="string", nullable=true)
     */
    protected $year;

    /**
     * @ORM\Column(name="available_date", type="string", nullable=true)
     */
    protected $availableDate;

    /**
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    protected $notes;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Tag", inversedBy="listings", cascade={"persist"})
     * @ORM\JoinTable(name="listing_tags",
     *     joinColumns={@ORM\JoinColumn(name="listing_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id",referencedColumnName="id", nullable=false)}
     * )
     */
    protected $tags;

    /**
     * @ORM\Column(name="meta_tags", type="string", nullable=true)
     */
    protected $metaTags;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Photo", mappedBy="listing", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="listing_id", nullable=false)
     */
    protected $photos;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\User", mappedBy="savedListings")
     */
    protected $savedBy;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\TradeOffer", mappedBy="listing")
     */
    protected $tradeOffers;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Trade", mappedBy="listing")
     */
    protected $trades;

    /**
     * @ORM\Column(name="views",type="integer")
     */
    protected $views;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->savedBy = new ArrayCollection();
        $this->tradeOffers = new ArrayCollection();
        $this->trades = new ArrayCollection();
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
     * @return MainCategory
     */
    public function getMainCategory()
    {
        return $this->mainCategory;
    }

    /**
     * @param MainCategory $mainCategory
     */
    public function setMainCategory($mainCategory): void
    {
        $this->mainCategory = $mainCategory;
        $mainCategory->addListing($this);
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
        $user->addListing($this);
    }

    /**
     * @return integer
     */
    public function getListingType()
    {
        return $this->listingType;
    }

    /**
     * @param integer $listingType
     */
    public function setListingType($listingType): void
    {
        $this->listingType = $listingType;
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
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return integer
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param integer $qty
     */
    public function setQty($qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @return integer
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param integer $condition
     */
    public function setCondition($condition): void
    {
        $this->condition = $condition;
    }

    /**
     * @return integer
     */
    public function getTradeOrCash()
    {
        return $this->tradeOrCash;
    }

    /**
     * @param integer $tradeOrCash
     */
    public function setTradeOrCash($tradeOrCash): void
    {
        $this->tradeOrCash = $tradeOrCash;
    }

    /**
     * @return boolean
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param boolean $shipping
     */
    public function setShipping($shipping): void
    {
        $this->shipping = $shipping;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand($brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * @param string $material
     */
    public function setMaterial($material): void
    {
        $this->material = $material;
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
     * @return mixed
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param mixed $availability
     */
    public function setAvailability($availability): void
    {
        $this->availability = $availability;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param $tag
     */
    public function addTag($tag): void
    {
        $this->tags[] = $tag;
    }

    /**
     * @param $tag
     */
    public function removeTag($tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param $photo
     */
    public function addPhoto($photo): void
    {
        $this->photos[] = $photo;
    }

    /**
     * @param $photo
     */
    public function removePhoto($photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * @return ArrayCollection
     */
    public function getSavedBy()
    {
        return $this->savedBy;
    }

    /**
     * @param User $user
     */
    public function addSavedBy($user)
    {
        $this->savedBy[] = $user;
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
    public function addTradeOffer($tradeOffer)
    {
        $this->tradeOffers[] = $tradeOffer;
    }

    /**
     * @return ArrayCollection
     */
    public function getTrades()
    {
        return $this->trades;
    }

    /**
     * @param Trade $trade
     */
    public function addTrade($trade)
    {
        $this->trades[] = $trade;
    }

    /**
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param integer $views
     */
    public function setViews($views): void
    {
        $this->views = $views;
    }

    /**
     * @return boolean
     */
    public function getLocalTradesOnly()
    {
        return $this->localTradesOnly;
    }

    /**
     * @param boolean $localTradesOnly
     */
    public function setLocalTradesOnly($localTradesOnly): void
    {
        $this->localTradesOnly = $localTradesOnly;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getAvailableDate()
    {
        return $this->availableDate;
    }

    /**
     * @param string $availableDate
     */
    public function setAvailableDate($availableDate): void
    {
        $this->availableDate = $availableDate;
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
     * @return string
     */
    public function getMetaTags()
    {
        return $this->metaTags;
    }

    /**
     * @param string $metaTags
     */
    public function setMetaTags($metaTags): void
    {
        $this->metaTags = $metaTags;
    }




    public function toArray($full_info = false)
    {
        $array = get_object_vars($this);

        foreach ($array as $key => $value) {
            if (is_object($value)) {
                unset($array[$key]);
            }
        }

        $mainCategory = $this->getMainCategory();
        $user = $this->getUser();
        $tags = $this->getTags();
        $photos = $this->getPhotos();
        $location = $this->getLocation();

        if(!empty($mainCategory)){
            $array["main_category_id"] = $mainCategory->getId();
            $array["mainCategory"] = [
                "id" => $mainCategory->getId(),
                "name" => $mainCategory->getName(),
                "icon" => $mainCategory->getIcon(),
                "colorCode" => $mainCategory->getColorCode(),
                "slug" => $mainCategory->getSlug()
            ];
        }

        if(!empty($user)) {
            $array["user"] = [
                "id" => $user->getId(),
                "name" => $user->getPublicName(),
                "rating" => $user->getRating(),
                "photo" => $user->getPhotoUrl(),
                "sample_account" => $user->getSampleAccount()
            ];
        }

        if(!empty($tags)) {
            $array["tags"] = [];
            foreach ($tags as $tag) {
                $array["tags"][] = [
                    "id" => $tag->getId(),
                    "name" => $tag->getName()
                ];
            }
        }

        if(!empty($photos)) {
            $array["photos"] = [];
            foreach ($photos as $photo) {
                $array["photos"][] = [
                    "id" => $photo->getId(),
                    "name" => $photo->getName(),
                    "order" => $photo->getOrder(),
                    "main" => $photo->getMain(),
                    "url" =>  $photo->getUrl()
                ];
            }
        }

        if(!empty($location)){
            $array["location"] = $location->getId();
            $array["locationCity"] = $location->getCity();
            $array["locationState"] = $location->getState();
            $array["locationText"] = $location->getLocationText();
        }

        return $array;
    }


}