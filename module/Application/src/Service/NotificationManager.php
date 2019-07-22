<?php

namespace Application\Service;

use Application\Entity\Conversation;
use Application\Entity\Listing;
use Application\Entity\Message;
use Application\Entity\MessageStatus;
use Application\Entity\Notification;
use Application\Entity\Trade;
use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\ServiceManager\ServiceManager;

class NotificationManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @param User $sender
     * @param User $receiver
     * @param int $type
     * @param Trade | Listing $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return Notification|boolean
     */
    public function addNotification($sender, $receiver, $type, $data)
    {
        $currentDate =  new \DateTime(date('Y-m-d H:i:s'));
        $notification = new Notification();

        switch ($type) {
            case Notification::TYPE_NEW_OFFER :
                $notification->setTitle("You have a new offer");
                $notification->setDescription("You have an offer from " . $sender->getPublicName() . " on " . $data->getListing()->getTitle());
                $notification->setLink("/dashboard/offers/my-listings/" . $data->getListing()->getId() . "/offer/" . $data->getId());
                break;
            case Notification::TYPE_TRADE_ACCEPTED:
                $notification->setTitle("Trade accepted");
                $notification->setDescription( $sender->getPublicName() . " accepted your offer on " . $data->getListing()->getTitle());
                $notification->setLink("/dashboard/trade-history/" . $data->getId());
                break;
            case Notification::TYPE_TRADE_REJECTED :
                $notification->setTitle("Trade rejected");
                $notification->setDescription( $sender->getPublicName() . " rejected your offer on " . $data->getListing()->getTitle());
                $notification->setLink("/dashboard/offers");
                break;
            case Notification::TYPE_SEARCH :
                $notification->setTitle("Saved Search");
                $notification->setDescription("There is a new listing that matches your search on " . $data->getTitle());
                $notification->setLink("link-test");
                break;
            case Notification::TYPE_TRADER_NEW_LISTING :
                $notification->setTitle("Follow Trader");
                $notification->setDescription($sender->getPublicName() . " has added a new listing: " . $data->getTitle());
                $notification->setLink("/listing-details/" . $data->getId());
                break;
            case Notification::TYPE_WATCHLIST_LISTING_UPDATED :
                $notification->setTitle("Watchlist update");
                $notification->setDescription($data->getTitle() . " from watchlist has been updated ");
                $notification->setLink("/listing-details/" . $data->getId());
                break;
            case Notification::TYPE_WATCHLIST_LISTING_NOT_AVAILABLE :
                $notification->setTitle("Watchlist not available");
                $notification->setDescription($data->getTitle() . " is no longer available");
                $notification->setLink("/dashboard/saved");
                break;
            case Notification::TYPE_WATCHLIST_LISTING_REPOSTED :
                $notification->setTitle("Watchlist reposted");
                $notification->setDescription($data->getTitle() . " was reposted again.");
                $notification->setLink("/listing-details/" . $data->getId());
                break;
            default:
                return false;
                break;
        }

        $notification->setUser($receiver);
        $notification->setType($type);
        $notification->setStatus(Notification::NOT_SEEN);
        $notification->setPhoto($sender->getPhotoUrl());
        $notification->setDateAdded($currentDate);
        $notification->setDateModified($currentDate);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $notification;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param mixed $serviceManager
     * @return $this
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}