<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MainCategory
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="main_category")
 */
class MainCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="icon")
     */
    protected $icon;

    /**
     * @ORM\Column(name="slug", type="string")
     */
    protected $slug;

    /**
     * @ORM\Column(name="color_code", type="string")
     */
    protected $colorCode;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Listing", mappedBy="mainCategory")
     * @ORM\JoinColumn(name="id",referencedColumnName="main_category_id", nullable=false)
     */
    protected $listings;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\Tag", mappedBy="mainCategory")
     * @ORM\JoinColumn(name="id", referencedColumnName="parent_id", nullable=false)
     */
    protected $tags;


    public function __construct()
    {
        $this->listings = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
    /**
     * @param string $icon
     */
    public function setIcon($icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return ArrayCollection
     */
    public function getListings()
    {
        return $this->listings;
    }

    /**
     * @param $listing
     */
    public function addListing($listing) : void
    {
        $this->listings[] = $listing;
    }

    /**
     * @param $listing
     */
    public function removeListing($listing) : void
    {
        $this->listings->removeElement($listing);
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
    public function addTag($tag) : void
    {
        $this->tags[] = $tag;
    }

    /**
     * @param $tag
     */
    public function removeTag($tag) : void
    {
        $this->tags->removeElement($tag);
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getColorCode()
    {
        return $this->colorCode;
    }

    /**
     * @param string $colorCode
     */
    public function setColorCode($colorCode): void
    {
        $this->colorCode = $colorCode;
    }



}