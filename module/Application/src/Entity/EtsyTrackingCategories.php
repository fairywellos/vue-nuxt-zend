<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Conversation
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="etsy_tracking_categories")
 */
class EtsyTrackingCategories
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\MainCategory", inversedBy="etsyCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false, onDelete="CASCADE"))
     */
    protected $mainCategory;

    /**
     * @ORM\Column(name="etsy_id",type="integer")
     */
    protected $etsyId;

    /**
     * @ORM\Column(name="name",type="string")
     */
    protected $name;

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
    }

    /**
     * @return mixed
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
}