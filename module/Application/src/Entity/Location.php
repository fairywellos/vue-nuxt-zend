<?php

namespace Application\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Location
 * @package Application\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\LocationRepository")
 * @ORM\Table(name="location")
 */
class Location implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="city", type="string")
     */
    protected $city;

    /**
     * @ORM\Column(name="state", type="string")
     */
    protected $state;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\ZipCode", mappedBy="location", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="location_id", nullable=false)
     */
    protected $zipCodes;

    public function __construct()
    {
        $this->zipCodes = new ArrayCollection();
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
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return ArrayCollection
     */
    public function getZipCodes()
    {
        return $this->zipCodes;
    }

    /**
     * @param ZipCode $zipCodes
     */
    public function addZipCode($zipCodes): void
    {
        $this->zipCodes[] = $zipCodes;
        $zipCodes->setLocation($this);
    }

    public function getLocationText()
    {
        return $this->getCity() . ", " . $this->getState();
    }

    /**
     * @param ZipCode $zipCodes
     */
    public function removeUser($zipCodes): void
    {
        $this->zipCodes->removeElement($zipCodes);
    }

    public function toArray()
    {
        return[
            "id" => $this->getId(),
            "city" => $this->getCity(),
            "state" => $this->getState(),
            "text" => $this->getLocationText()
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}