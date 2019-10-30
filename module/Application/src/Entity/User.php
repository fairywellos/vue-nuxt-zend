<?php


namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Crypt\Password\Bcrypt;

/**
 * Class User
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="user",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="email_unique",columns={"email"})},
 *     indexes={@ORM\Index(name="email_index", columns={"email"}),
 *              @ORM\Index(name="user_type_index",columns={"user_type"}),
 *              @ORM\Index(name="status_index",columns={"status"}),
 *              @ORM\Index(name="public_index",columns={"public"}),
 *              @ORM\Index(name="activation_code_index",columns={"activation_code"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="Application\Repository\UserRepository")
 */
class User
{
    //User type constants
    const ADMIN = 1;
    const BASIC = 2;
    const PAID = 3;
    const BUSINESS = 4;
    const SAMPLE = 5;

    //User status constants
    const NOT_ACTIVATED = 1;
    const ACTIVE = 2;
    const INACTIVE = 3;

    //User visibility type
    const PUBLIC = 1;
    const PRIVATE = 2;

        /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(name="id",type="integer")
         */
        protected $id;

    /**
     * @ORM\Column(name="first_name", type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(name="email", type="string", unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(name="password")
     */
    protected $password;

    /**
     * @ORM\Column(name="user_type", type="smallint")
     */
    protected $userType;

    /**
     * @ORM\Column(name="status", type="smallint", options={"default":1})
     */
    protected $status;

    /**
     * @ORM\Column(name="rating",type="decimal", scale=2, precision=4, nullable=true)
     */
    protected $rating;

    /**
     * @ORM\Column(name="public", type="smallint", options={"default":1})
     */
    protected $public;

    /**
     * @ORM\Column(name="privacy", type="smallint", options={"default":2})
     */
    protected $privacy;

    /**
     * @ORM\Column(name="date_added", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="date_modified", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateModified;

    /**
     * @ORM\Column(name="membership_expires", type="datetimetz", nullable=false)
     */
    protected $membershipExpires;

    /**
     * @ORM\Column(type="smallint",name="credits", options={"default":3})
     */
    protected $credits;

    /**
     *  @ORM\ManyToOne(targetEntity="\Application\Entity\Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id",nullable=true)
     */
    protected $location;

    /**
     * @ORM\Column(type="string", name="username" , nullable=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", name="photo", nullable=true)
     */
    protected $photo;

    /**
     * @ORM\Column(type="string", name="photo_url",nullable=true)
     */
    protected $photoUrl;

    /**
     * @ORM\Column(type="text", name="description", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string",name="activation_code")
     */
    protected $activationCode;

    /**
     * @ORM\Column(name="activation_expires", type="datetimetz", nullable=false)
     */
    protected $activationExpires;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Listing", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id", nullable=false)
     */
    protected $listings;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Listing", inversedBy="savedBy")
     * @ORM\JoinTable(name="saved_listing",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="listing_id", referencedColumnName="id", onDelete="CASCADE")}
     *  )
     */
    protected $savedListings;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\SavedSearch", inversedBy="savedBy", cascade={"persist"})
     * @ORM\JoinTable(name="user_saved_search",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="search_id", referencedColumnName="id")}
     *  )
     */
    protected $userSavedSearches;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Notification", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id", nullable=false)
     */
    protected $notifications;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Conversation", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="conversation_user",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="conversation_id",referencedColumnName="id", nullable=false)}
     * )
     */
    protected $conversations;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\User", inversedBy="followers", cascade={"persist"})
     * @ORM\JoinTable(name="following",
     *     joinColumns={@ORM\JoinColumn(name="follower_id", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id",referencedColumnName="id", nullable=false)}
     * )
     */
    protected $usersFollowing;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\User", mappedBy="usersFollowing")
     */
    protected $followers;

    public function __construct()
    {
        $this->listings          = new ArrayCollection();
        $this->savedListings     = new ArrayCollection();
        $this->userSavedSearches = new ArrayCollection();
        $this->conversations     = new ArrayCollection();
        $this->notifications     = new ArrayCollection();
        $this->usersFollowing    = new ArrayCollection();
        $this->followers         = new ArrayCollection();
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return integer
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param integer $userType
     */
    public function setUserType($userType): void
    {
        $this->userType = $userType;
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
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return integer
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param integer $public
     */
    public function setPublic($public): void
    {
        $this->public = $public;
    }

    /**
     * @return integer
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param integer $privacy
     */
    public function setPrivacy($privacy): void
    {
        $this->privacy = $privacy;
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

    /**
     * @param $listing
     */
    public function removeListing($listing): void
    {
        $this->listings->removeElement($listing);
    }

    /**
     * @return ArrayCollection
     */
    public function getSavedListings()
    {
        return $this->savedListings;
    }

    /**
     * @param Listing $listing
     */
    public function addSavedListing($listing): void
    {
        $this->savedListings[] = $listing;
    }

    /**
     * @param Listing $listing
     */
    public function removeSavedListing($listing): void
    {
        $this->savedListings->removeElement($listing);
    }

    /**
     * @return ArrayCollection
     */
    public function getUserSavedSearches()
    {
        return $this->userSavedSearches;
    }

    /**
     * @param SavedSearch $savedSearch
     */
    public function addUserSavedSearch($savedSearch): void
    {
        $this->userSavedSearches[] = $savedSearch;
    }

    /**
     * @param SavedSearch $savedSearch
     */
    public function removeUserSavedSearch($savedSearch): void
    {
        $this->userSavedSearches->removeElement($savedSearch);
    }

    /**
     * @return ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param $notification
     */
    public function addNotification($notification): void
    {
        $this->notifications[] = $notification;
    }

    /**
     * @param $notification
     */
    public function removeNotification($notification): void
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password): void
    {
        $bcrypt = new Bcrypt();
        $password_crypt = $bcrypt->create($password);
        $this->password = $password_crypt;
    }

    /**
     * @return mixed
     */
    public function getMembershipExpires()
    {
        return $this->membershipExpires;
    }

    /**
     * @param mixed $membershipExpires
     */
    public function setMembershipExpires($membershipExpires): void
    {
        $this->membershipExpires = $membershipExpires;
    }

    /**
     * @return integer
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * @param integer $credits
     */
    public function setCredits($credits): void
    {
        $this->credits = $credits;
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    /**
     * @param string $photoUrl
     */
    public function setPhotoUrl($photoUrl): void
    {
        $this->photoUrl = $photoUrl;
    }



    /**
     * @return string
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * @param string $activationCode
     */
    public function setActivationCode($activationCode): void
    {
        $this->activationCode = $activationCode;
    }

    /**
     * @return mixed
     */
    public function getActivationExpires()
    {
        return $this->activationExpires;
    }

    /**
     * @param mixed $activationExpires
     */
    public function setActivationExpires($activationExpires): void
    {
        $this->activationExpires = $activationExpires;
    }

    /**
     * @return ArrayCollection
     */
    public function getConversations()
    {
        return $this->conversations;
    }

    /**
     * @param $conversation
     */
    public function addConversation($conversation): void
    {
        $this->conversations[] = $conversation;
    }

    /**
     * @param $conversation
     */
    public function removeConversation($conversation): void
    {
        $this->conversations->removeElement($conversation);
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

    public function getPublicName()
    {
        if($this->privacy == User::PUBLIC){
            $full_name = $this->firstName . " " . $this->name;
            if($full_name !== " "){
                return $full_name;
            }
            return null;
        }else{
            return $this->username;
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getUsersFollowing()
    {
        return $this->usersFollowing;
    }

    /**
     * @param User $user
     */
    public function follow($user): void
    {
        $this->usersFollowing[] = $user;
    }

    /**
     * @param $user
     */
    public function unfollow($user): void
    {
        $this->usersFollowing->removeElement($user);
    }

    /**
     * @return ArrayCollection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    public function getSampleAccount()
    {
        if($this->userType == User::SAMPLE){
            return 1;
        }else{
            return 0;
        }
    }

    public function toArray()
    {
        $array = get_object_vars($this);
        $array["publicName"] = $this->getPublicName();
        return $array;
    }

    public function getPublicInfo()
    {
        return [
          "id" => $this->getId(),
          "name" => $this->getPublicName(),
          "photo" => $this->getPhotoUrl(),
        ];
    }

    public function getProfileData($myProfile = false)
    {
        $result = [
            "id" => $this->getId(),
            "name" => $this->getPublicName(),
            "photo" => $this->getPhotoUrl(),
            "description" => $this->getDescription(),
        ];
        $location = $this->getLocation();
        if(!empty($location)){
            $result["location"] = $location->getId();
            $result["locationCity"] = $location->getCity();
            $result["locationState"] = $location->getState();
            $result["locationText"] = $location->getLocationText();
        }else{
            $result["location"] = "";
            $result["locationCity"] = "";
            $result["locationState"] = "";
            $result["locationText"] =  "";
        }
        if($myProfile){
            $result["firstName"] = $this->getFirstName();
            $result["lastName"] = $this->getName();
            $result["userName"] = $this->getUsername();
            $result["email"] = $this->getEmail();

        }elseif (!$myProfile){
            $result["dataAdded"] = $this->getDateAdded();
            $result["followers"] = $this->getFollowers()->count();
            $result["rating"] = $this->getRating();
            $result["sample_account"] = $this->getSampleAccount();
        }

        return $result;
    }

    public function  getAuthInfo()
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'name' => $this->getName(),
            'firstName' => $this->getFirstName(),
            'userType' => $this->getUserType(),
            'public' => $this->getPublic(),
            'privacy' => $this->getPrivacy(),
            'dateAdded' => $this->getDateAdded(),
            'rating' => $this->getRating(),
            'photo' => $this->getPhotoUrl(),
            'credits' => $this->getCredits(),
            'username' => $this->getUsername(),
            'membershipExpires' => $this->getMembershipExpires(),
            'location' => $this->getLocation(),
            'publicName' => $this->getPublicName(),
        ];
    }

}