<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ZipCode
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="zip_code")
 */
class ZipCode
{
    /**
     * @ORM\Id
     * @ORM\Column(name="zip_code",type="integer")
     */
    protected $zipCode;

    /**
     * @ORM\Column(name="type", type="string")
     */
    protected $type;

    /**
     * @ORM\Column(name="lat", type="float")
     */
    protected $lat;

    /**
     * @ORM\Column(name="long", type="float")
     */
    protected $long;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Location", inversedBy="zipCodes")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=false)
     */
    protected $location;

    /**
     * @return integer
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param integer $zipCode
     */
    public function setZipCode($zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat($lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * @param float $long
     */
    public function setLong($long): void
    {
        $this->long = $long;
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
}