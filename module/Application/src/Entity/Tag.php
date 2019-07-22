<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tag
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="tag",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_tag", columns={"name", "parent_id"})},
 *     indexes={@ORM\Index(name="parent_id_index", columns={"parent_id"})}
 *     )
 * @ORM\Entity(repositoryClass="Application\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\MainCategory", inversedBy="tags")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=false)
     */
    protected $mainCategory;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Listing", mappedBy="tags")
     */
    protected $listings;


    public function __construct()
    {
        $this->listings = new ArrayCollection();
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
        $mainCategory->addTag($this);
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
    public function addListing($listing): void
    {
        $this->listings[] = $listing;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}