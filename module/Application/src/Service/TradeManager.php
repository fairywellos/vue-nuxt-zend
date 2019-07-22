<?php

namespace Application\Service;


use Application\Entity\Listing;
use Application\Entity\Notification;
use Application\Entity\Trade;
use Application\Entity\TradeOffer;
use Application\Entity\User;
use Doctrine\ORM\EntityManager;

class TradeManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PusherManager
     */
    private $pusherManager;

    /**
     * @var NotificationManager
     */
    private $notificationManager;

    public function __construct($entityManager,$pusherManager,$notificationManager)
    {
        $this->entityManager = $entityManager;
        $this->pusherManager = $pusherManager;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @param $data
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addTrade($data,$user)
    {
        $entity = new Trade();
        $entity->setListing($data["listing"]);
        $entity->setStatus($data["status"]);
        $entity->setTradeType($data["tradeType"]);
        $entity->setNotes($data["notes"]);
        $entity->setRated($data["rated"]);
        $entity->setPaid($data["paid"]);
        $entity->setDateAdded($data["dateAdded"]);
        foreach ($data["tradeOffers"] as $item){
            $tradeOffer = new TradeOffer();
            $tradeOffer->setListing($item["listing"]);
            $tradeOffer->setUser($item["user"]);
            $tradeOffer->setCashValue($item["cashValue"]);

            $tradeOffer->setTrade($entity);
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $notification = $this->notificationManager->addNotification($user,$entity->getListing()->getUser(),Notification::TYPE_NEW_OFFER,$entity);
        $this->pusherManager->sendNotification($entity->getListing()->getUser(),$notification->toArray());
    }

    public  function prepareData(User $user,$tradeType,Listing $listing,$cashValue = 0, $listingsOffered = [] )
    {
        $data = [];
        $data["listing"] = $listing;
        $data["status"] = Trade::PENDING;
        $data["tradeType"] = $tradeType;
        $data["notes"] = null;
        $data["rated"] = 0;
        $data["paid"] = 0;
        $dateAdded  = new \DateTime(date('Y-m-d H:i:s'));
        $data["dateAdded"] = $dateAdded;
        if($tradeType == Trade::CASH_OFFER){
            $data["tradeOffers"][] =[
                "listing" => null,
                "user" => $user,
                "cashValue" => $cashValue,
            ];
        }elseif ($tradeType == Trade::ITEM_TRADE){
            foreach ($listingsOffered as $item){
                $data["tradeOffers"][] = [
                    "listing" => $item,
                    "user" => $user,
                    "cashValue" => $cashValue,
                ];
            }
        }elseif ($tradeType == Trade::MIXED_OFFER){
            foreach ($listingsOffered as $item){
                $data["tradeOffers"][] = [
                    "listing" => $item,
                    "user" => $user,
                    "cashValue" => $cashValue,
                ];
                $cashValue = 0;
            }
        }

        return $data;
    }

    /**
     * @param Trade $trade
     * @param $status
     * @param User $user
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateStatus(Trade $trade, $status,$user)
    {
        $message = "Default message";
        switch ($status){
            case "accept":
                $this->autoRejectTradeOffers($trade,$user);
                $trade->setStatus(Trade::ACCEPTED);
                $message = "Trade offer accepted";
                $notification = $this->notificationManager->addNotification($user,$trade->getTradeOffers()->first()->getUser(),Notification::TYPE_TRADE_ACCEPTED,$trade);
                $this->pusherManager->sendNotification($trade->getTradeOffers()->first()->getUser(),$notification->toArray());
                break;
            case "reject":
                $trade->setStatus(Trade::REJECTED);
                $message = "Trade offer rejected";
                $notification = $this->notificationManager->addNotification($user,$trade->getTradeOffers()->first()->getUser(),Notification::TYPE_TRADE_REJECTED,$trade);
                $this->pusherManager->sendNotification($trade->getTradeOffers()->first()->getUser(),$notification->toArray());
                break;
            case "pending":
                //TODO: ....
                break;
        }

        $this->entityManager->persist($trade);
        $this->entityManager->flush();

        return $message;
    }

    /**
     * @param Trade $trade
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     */
    private function autoRejectTradeOffers(Trade $trade, $user)
    {
        $this->autoRejectListingTradeOffers($trade,$user);
        if($trade->getTradeType() != Trade::CASH_OFFER){
            $this->autoRejectItemsTradeOffers($trade);
        }
    }

    /**
     * Reject all other trade offers made for the Listing associated with the Trade
     *
     * @param Trade $trade
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     */
    private function autoRejectListingTradeOffers(Trade $trade, $user): void
    {
        $listing = $trade->getListing();
        $trades = $listing->getTrades();
        /**
         * @var  Trade $subTrade
         */
        foreach ($trades as $subTrade){
            if($subTrade->getId()  != $trade->getId()){
                $subTrade->setStatus(Trade::REJECTED);
                $this->entityManager->persist($subTrade);
                $notification = $this->notificationManager->addNotification($user,$subTrade->getTradeOffers()->first()->getUser(),Notification::TYPE_TRADE_REJECTED,$subTrade);
                $this->pusherManager->sendNotification($subTrade->getTradeOffers()->first()->getUser(),$notification->toArray());
            }
        }
        $listing->setStatus(Listing::TRADED);
        $this->entityManager->persist($listing);

        $savedBy = $listing->getSavedBy();
        /**
         * @var User $user
         */
        foreach ($savedBy as $user){
            $this->notificationManager->addNotification($listing->getUser(),$user,Notification::TYPE_WATCHLIST_LISTING_NOT_AVAILABLE,$listing);
        }
    }

    /**
     * If any listings where accepted in the trade, make all other trade offers where the listing was used unavailable
     *
     * @param Trade $trade
     * @throws \Doctrine\ORM\ORMException
     */
    private function autoRejectItemsTradeOffers(Trade $trade)
    {
        $tradeOffers = $trade->getTradeOffers();
        /**
         * @var TradeOffer $tradeOffer
         */
        foreach ($tradeOffers as $tradeOffer)
        {
            $listing = $tradeOffer->getListing();
            if($listing){
                $itemTradeOffers = $listing->getTradeOffers();
                /**
                 * @var TradeOffer $itemTradeOffer
                 */
                foreach ($itemTradeOffers as $itemTradeOffer){
                    $itemTrade = $itemTradeOffer->getTrade();
                    $itemTrade->setStatus(Trade::UNAVAILABLE);
                    $this->entityManager->persist($itemTrade);
                }
                $listing->setStatus(Listing::TRADED);
                $this->entityManager->persist($listing);
                $savedBy = $listing->getSavedBy();
                /**
                 * @var User $user
                 */
                foreach ($savedBy as $user){
                    $this->notificationManager->addNotification($listing->getUser(),$user,Notification::TYPE_WATCHLIST_LISTING_NOT_AVAILABLE,$listing);
                }

            }
        }
    }
}