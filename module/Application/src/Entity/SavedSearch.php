<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class SavedSearch
 * @package Application\Entity
 * @ORM\ Entity
 * @ORM\Table(name="saved_search",
 *     indexes={@ORM\Index(name="query_index", columns={"query"})
 *      }
 * )
 */
class SavedSearch
{
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
     * @ORM\Column(name="query", type="string")
     */
    protected $query;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\User", mappedBy="userSavedSearches")
     */
    protected $savedBy;

    public function __construct()
    {
        $this->savedBy = new ArrayCollection();
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
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery($query): void
    {
        $this->query = $query;
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

    public function toArray(){
        $result = get_object_vars($this);
        unset($result['savedBy']);

        return $result;
    }
}