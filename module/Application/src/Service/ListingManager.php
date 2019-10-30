<?php

namespace Application\Service;


use Application\Entity\Listing;
use Application\Entity\MainCategory;
use Application\Entity\Notification;
use Application\Entity\Photo;
use Application\Entity\Tag;
use Application\Entity\Trade;
use Application\Entity\TradeOffer;
use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;

class ListingManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UploadManager
     */
    private $uploadManager;

    /**
     * @var PusherManager
     */
    private $pusherManager;

    /**
     * @var NotificationManager
     */
    private $notificationManager;

    public function __construct($entityManager, $uploadManager,$pusherManager,$notificationManager)
    {
        $this->entityManager = $entityManager;
        $this->uploadManager = $uploadManager;
        $this->pusherManager = $pusherManager;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @param array $data
     * @param User $currentUser
     * @param Listing $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function editListing($data, $currentUser, $entity)
    {


        if (!empty($data['unlink_photos'])) {
            foreach ($data['unlink_photos'] as $unlinkImage) {
                $image = $this->entityManager->getRepository(Photo::class)->find($unlinkImage['id']);

                $this->entityManager->remove($image);
            }
        }

        $this->addListing($data, $currentUser, $entity);
    }

    /**
     * @param array $data
     * @param User $currentUser
     * @param Listing|NULL $entity
     * @return int
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addListing($data, $currentUser, $entity = null)
    {
        /**
         * @var $mainCategory MainCategory
         */
        $mainCategory = $this->entityManager->getRepository(MainCategory::class)->find($data['main_category_id']);

        if($entity){
            $notificationType =  Notification::TYPE_WATCHLIST_LISTING_UPDATED ;
            $currentDate = new \DateTime(date('Y-m-d H:i:s'));
            $entity->setDateModified($currentDate);
        }else{
            $notificationType =  Notification::TYPE_TRADER_NEW_LISTING;
            $entity = new Listing();
            $currentDate = new \DateTime(date('Y-m-d H:i:s'));
            $availability = new \DateTime(date('Y-m-d H:i:s', strtotime('+1 month')));
            $entity->setDateAdded($currentDate);
            $entity->setDateModified($currentDate);
            $entity->setAvailability($data['availability'] ?? $availability);

        }

        $entity->setUser($currentUser);
        $entity->setViews(0);
        $entity->setMainCategory($mainCategory);
        $entity->setListingType($data['listingType']);
        $entity->setStatus(Listing::ACTIVE);
        $entity->setTitle($data['title']);
        $entity->setDescription($data['description']);
        $entity->setPrice($data['price']);
        $entity->setQty($data['qty']);
        $entity->setCondition($data['condition']);
        $entity->setTradeOrCash($data['tradeOrCash']);
        $entity->setShipping($data['shipping']);
        $entity->setLocation($data['location']);
        $entity->setBrand($data['brand']);
        $entity->setColor($data['color']);
        $entity->setMaterial($data['material']);
        $entity->setLocalTradesOnly($data["localTradesOnly"]);
        $entity->setYear($data["year"]);
        $entity->setAvailableDate($data["available_date"]);
        $entity->setNotes($data["notes"]);
        $entity->setMetaTags($data["meta_tags"]);

        if (!empty($data['listing_tags']) && is_array($data['listing_tags'])) {
            foreach ($entity->getTags() as $currentTag) {
                $entity->removeTag($currentTag);
            }

            foreach ($data['listing_tags'] as $tagName) {
                $tagName = trim(strtolower($tagName));
                $tag = $this->entityManager->getRepository(Tag::class)->findOneBy(['name' => $tagName, 'mainCategory' => $mainCategory]);
                if (empty($tag)) {
                    $tag = new Tag();
                    $tag->setName($tagName);
                    $tag->setMainCategory($mainCategory);
                }

                $entity->addTag($tag);
            }
        }

        if (!empty($data['photos'])) {
            foreach ($data['photos'] as $image) {
                $photo = new Photo();
                $photo->setName($image['name'] ?? null);
                $photo->setMain($image['main'] ?? 0);
                $photo->setOrder($image['order'] ?? 0);
                $photo->setListing($entity);
            }
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        if($notificationType == Notification::TYPE_TRADER_NEW_LISTING){

            $followers = $currentUser->getFollowers();
            /**
             * @var User $follower
             */
            foreach ($followers as $follower){
                $this->notificationManager->addNotification($currentUser,$follower,$notificationType,$entity);
            }
        }else if ($notificationType == Notification::TYPE_WATCHLIST_LISTING_UPDATED){
            $savedBy = $entity->getSavedBy();
            /**
             * @var User $user
             */
            foreach ($savedBy as $user){
                $this->notificationManager->addNotification($currentUser,$user,$notificationType,$entity);
            }

        }

        return $entity->getId();
    }

    /**
     * @param int $listingId
     * @param bool $status
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setSaved($listingId, $status, $user)
    {
        /**
         * @var Listing $listing
         */
        $listing = $this->entityManager->getRepository(Listing::class)->findOneBy(['id' => $listingId]);

        if ((int)$status === Listing::ADD_SAVED) {
            $user->addSavedListing($listing);
        } else {
            $user->removeSavedListing($listing);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param Listing $listing
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteListing(Listing $listing)
    {
        $this->entityManager->remove($listing);
        $photos = $listing->getPhotos();
        /**
         * @var Photo $photo
         */
        foreach ($photos as $photo) {
            try {

                $this->uploadManager->deleteFile('listing', $photo->getName());
            } catch (\Exception $exception) {
                error_log("Caught $exception");
            }
        }

        $this->rejectTrades($listing);
        $this->deleteTradesMade($listing);

        $this->entityManager->flush();

        $savedBy = $listing->getSavedBy();
        /**
         * @var User $user
         */
        foreach ($savedBy as $user){
            $this->notificationManager->addNotification($listing->getUser(),$user,Notification::TYPE_WATCHLIST_LISTING_NOT_AVAILABLE,$listing);
        }

    }

    /**
     * @param Listing $listing
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function repostListing(Listing $listing)
    {
        $listing->setStatus(Listing::ACTIVE);
        $currentDate = new \DateTime(date('Y-m-d H:i:s'));
        $availability = new \DateTime(date('Y-m-d H:i:s', strtotime('+1 month')));
        $listing->setDateModified($currentDate);
        $listing->setAvailability($availability);
        $this->entityManager->persist($listing);
        $this->entityManager->flush();

        $savedBy = $listing->getSavedBy();
        /**
         * @var User $user
         */
        foreach ($savedBy as $user){
            $this->notificationManager->addNotification($listing->getUser(),$user,Notification::TYPE_WATCHLIST_LISTING_REPOSTED,$listing);
        }
    }

    /**
     * @param Listing $listing
     * @throws \Doctrine\ORM\ORMException
     */
    private function rejectTrades(Listing $listing)
    {
        $trades = $listing->getTrades();
        /**
         * @var Trade $trade
         */
        foreach ($trades as $trade) {
            $trade->setStatus(Trade::UNAVAILABLE);
            $this->entityManager->persist($trade);
        }
    }

    /**
     * @param Listing $listing
     * @throws \Doctrine\ORM\ORMException
     */
    private function deleteTradesMade(Listing $listing)
    {
        $trade_offers = $listing->getTradeOffers();
        /**
         * @var TradeOffer $trade_offer
         */
        foreach ($trade_offers as $trade_offer){
            $trade = $trade_offer->getTrade();
            $this->entityManager->remove($trade);
        }
    }

}