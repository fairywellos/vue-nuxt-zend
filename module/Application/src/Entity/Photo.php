<?php

namespace Application\Entity;

use Application\Service\UploadManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Photo
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="photo",
 *     indexes={@ORM\Index(name="main_index", columns={"main"}),
 *              @ORM\Index(name="order_index",columns={"photo_order"}),
 *              @ORM\Index(name="listing_id_index",columns={"listing_id"})
 *      }
 * )
 */
class Photo
{
    //Photo main constant
    const MAIN      = 1;
    const DEFAULT   = 0;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="photo_name",type="string")
     */
    protected $name;

    /**
     *  @ORM\Column(name="photo_order",type="smallint")
     */
    protected  $order;

    /**
     *  @ORM\Column(name="main",type="boolean")
     */
    protected $main;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Listing", inversedBy="photos")
     * @ORM\JoinColumn(name="listing_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $listing;


    public $url;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        $uploadManager = new UploadManager();
        $this->setUrl($uploadManager->getFileUrl("listing", $this->getName()));
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function __construct()
    {
        $uploadManager = new UploadManager();
        $this->setUrl($uploadManager->getFileUrl("listing", $this->getName()));
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param integer $order
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }

    /**
     * @return boolean
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * @param boolean $main
     */
    public function setMain($main): void
    {
        $this->main = $main;
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
        $listing->addPhoto($this);
    }

    public function toArray()
    {
        return get_object_vars($this);
    }


}