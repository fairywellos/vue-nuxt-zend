<?php

namespace Application\Controller;

use Application\Entity\Listing;
use Application\Entity\Location;
use Application\Entity\Notification;
use Application\Entity\Trade;
use Application\Entity\User;
use Application\Form\ListingForm;
use Application\Form\SetSavedListingForm;
use Application\Repository\ListingRepository;
use Application\Repository\TradeRepository;
use Application\Service\ListingManager;
use Application\Service\NotificationManager;
use Application\Service\PusherManager;
use Application\Service\TradeManager;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\Db\Sql\Ddl\Column\Datetime;

/**
 * Class ListingController
 *
 * @method User currentUser()
 * @method Plugin\IsDevClientPlugin isDevClient()
 * @package Application\Controller
 */
class ListingController extends ApiController
{
    /**
     * @var ListingManager
     */
    private $listingManager;

    /**
     * Entity manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TradeManager
     */
    private $tradeManager;



    public function __construct($listingManager, $entityManager, $tradeManager,$pusherManager,$notificationManager)
    {
        $this->entityManager = $entityManager;
        $this->listingManager = $listingManager;
        $this->tradeManager = $tradeManager;
    }

    public function indexAction()
    {
        $data = $this->params()->fromQuery();

        if (empty($data["id"])) {
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No listing id provided";
            return $this->createResponse();
        }

        /**
         * @var Listing $listing
         */
        $listing = $this->entityManager->getRepository(Listing::class)->findOneBy(['id' => $data["id"]]);

        if ($listing == null) {
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No listing found for id: " . $data["id"];
            return $this->createResponse();
        }

        $response_data = $listing->toArray();

        $this->httpStatusCode = 200;
        $this->apiResponse['data'] = $response_data;
        return $this->createResponse();
    }

    public function addAction()
    {
        $form = new ListingForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();
                if (!empty($data["photos"])) {
                    $data["photos"][0]["main"] = 1;
                }
                if(!empty($data["location"])){
                    $location = $this->entityManager->getRepository(Location::class)->findOneBy(["id" =>$data["location"]]);
                    if ($location == null){
                        $this->httpStatusCode = 400;
                        $form->setMessages(["location" => "Invalid location selected"]);
                        $this->apiResponse = $form->getMessages();
                        return $this->createResponse();
                    }
                    $data["location"] = $location;
                }
                try {
                    if (!empty($data['id'])) {

                        /**
                         * @var Listing $entity
                         */
                        $entity = $this->entityManager->getRepository(Listing::class)
                            ->findOneBy(['id' => $data['id'], 'user' => $this->currentUser()->getId()]);

                        if (!$entity) {
                            $this->httpStatusCode = 404;
                            $this->apiResponse["message"] = "You have no listing with id:" . $data['id'];
                            return $this->createResponse();
                        }else if ($entity->getStatus() === Listing::TRADED){
                            $this->httpStatusCode = 403;
                            $this->apiResponse["message"] = "You can not edit traded listings";
                            return $this->createResponse();
                        }
                        $this->listingManager->editListing($data, $this->currentUser(), $entity);
                    } else {
                        $this->listingManager->addListing($data, $this->currentUser());
                    }

                    $this->httpStatusCode = 200;
                    $this->apiResponse["message"] = "Listing added successfully";
                } catch (\Exception $exception) {
                    $this->httpStatusCode = 500;
                    $this->apiResponse["message"] = "Something went wrong";

                    if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                        $this->apiResponse["error_message"] = $exception->getMessage();
                    } else {
                        error_log("Caught $exception");
                    }
                }
            } else {
                $this->httpStatusCode = 400;
                $this->apiResponse = $form->getMessages();
            }
        }

        return $this->createResponse();
    }

    public function setSavedAction()
    {
        $form = new SetSavedListingForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                try {
                    $listingId = (int)$data['listing_id'];
                    $status = (int)$data['status'];
                    $this->listingManager->setSaved($listingId, $status, $this->currentUser());

                    $this->httpStatusCode = 200;
                    if ($status === Listing::ADD_SAVED) {
                        $this->apiResponse["message"] = "Listing added successfully";
                    } else {
                        $this->apiResponse["message"] = "Listing removed successfully";
                    }
                } catch (\Exception $exception) {
                    $this->httpStatusCode = 500;
                    $this->apiResponse["message"] = "Something went wrong";

                    if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                        $this->apiResponse["error_message"] = $exception->getMessage();
                    } else {
                        error_log("Caught $exception");
                    }
                }
            } else {
                $this->httpStatusCode = 400;
                $this->apiResponse = $form->getMessages();
            }
        }

        return $this->createResponse();
    }

    public function feedAction()
    {
        $params = $this->params()->fromQuery();

        $routeMatch = $this->getEvent()->getRouteMatch();
        $routeName = $routeMatch->getMatchedRouteName();

        if ($routeName === 'listing/feed/currentUser/getUserListings') {
            $params["user"] = $this->currentUser()->getId();
            if (!isset($params["status"])) {

                $params["status"] = "all";
            }

        }

        if ($routeName === 'listing/feed/saved/getSaved') {
            $params["show_saved"] = true;
        }

        /**
         * @var $listingRepository ListingRepository
         */
        $listingRepository = $this->entityManager->getRepository(Listing::class);

        $listingRepository->setCurrentUser($this->currentUser());

        $listings = $listingRepository->filterBy($params);

        $this->httpStatusCode = 200;
        $this->apiResponse['data'] = $listings;

        if (
            (isset($params['q']) && strlen($params['q']) >= ListingRepository::MIN_TEXT_LENGTH_Q)
            || ($routeName === 'listing/feed/saved/getSaved')
        ) {
            $this->apiResponse['total_results'] = $listingRepository->getTotalItems();
        }

        /**
         * Navigation
         */
        if ($listingRepository->getPage() !== ListingRepository::FIRST_PAGE) {
            if (($listingRepository->getPage() * $listingRepository->getMaxResults()) > $listingRepository->getTotalItems()) {
                $previewPageNumber = ceil($listingRepository->getTotalItems() / $listingRepository->getMaxResults());
            } else {
                $previewPageNumber = $listingRepository->getPage() - 1;
            }

            $this->apiResponse['preview_page_number'] = $previewPageNumber;
            $this->apiResponse['preview_page_url'] = $this->changePageNo(
                $this->getCurrentUrl(), $previewPageNumber
            );
        }

        if ($listingRepository->getTotalItems() > ($listingRepository->getPage() * $listingRepository->getMaxResults())) {
            $nextPageNumber = $listingRepository->getPage() + 1;
            $this->apiResponse['next_page_number'] = $nextPageNumber;
            $this->apiResponse['next_page_url'] = $this->changePageNo(
                $this->getCurrentUrl(),
                $nextPageNumber
            );
        }

        return $this->createResponse();
    }

    public function tradeOfferAction()
    {
        $data = $this->params()->fromPost("params");
        if (isset($data["user"]) && isset($data["listingId"]) && isset($data["offerType"])) {

            $user = $this->currentUser();
            if ($user == null) {
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = "This actions is allowed only to logged in users";
                return $this->createResponse();
            }
            /**
             * @var $listing Listing
             */
            $listing = $this->entityManager->getRepository(Listing::class)->findOneBy(['id' => $data["listingId"]]);
            if ($listing == null) {
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "No listing found for id: " . $data["id"];
                return $this->createResponse();
            }
            $currentDate = new \DateTime();//TODO: date validations
            if ($listing->getStatus() !== Listing::ACTIVE) {
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = "This listing is no longer active";
                return $this->createResponse();
            }
            if ($listing->getUser()->getId() == $user->getId()) {
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = "You can not make a trade offer on your own listing";
                return $this->createResponse();
            }
            /**
             * @var $tradeRepository TradeRepository
             */
            $tradeRepository = $this->entityManager->getRepository(Trade::class);

            if ($data["offerType"] == 1) {

                $tradeData = $this->tradeManager->prepareData($user, Trade::CASH_OFFER, $listing, $listing->getPrice());
                $result = $tradeRepository->checkForDuplicateCase1($tradeData, $user);
                if (!empty($result)) {
                    $this->httpStatusCode = 400;
                    $this->apiResponse["message"] = "You already made an identical trade offer.";
                    return $this->createResponse();
                }

            } elseif ($data["offerType"] == 2) {
                if (empty($data["listingsOffered"]) && $data["cashOffer"] > 0) {
                    $tradeData = $this->tradeManager->prepareData($user, Trade::CASH_OFFER, $listing, $data["cashOffer"]);
                    $result = $tradeRepository->checkForDuplicateCase1($tradeData, $user);
                    if (!empty($result)) {
                        $this->httpStatusCode = 400;
                        $this->apiResponse["message"] = "You already made an identical trade offer.";
                        return $this->createResponse();
                    }
                } elseif (!empty($data["listingsOffered"]) && $data["cashOffer"] == 0) {
                    $listingsArray = $this->entityManager->getRepository(Listing::class)->findById($data["listingsOffered"]);
                    $tradeData = $this->tradeManager->prepareData($user, Trade::ITEM_TRADE, $listing, 0, $listingsArray);
                } elseif (!empty($data["listingsOffered"]) && $data["cashOffer"] > 0) {
                    $listingsArray = $this->entityManager->getRepository(Listing::class)->findById($data["listingsOffered"]);
                    $tradeData = $this->tradeManager->prepareData($user, Trade::MIXED_OFFER, $listing, $data["cashOffer"], $listingsArray);
                } else {
                    $this->httpStatusCode = 400;
                    $this->apiResponse["message"] = "To make a trade offer please select a listing to trade or make a cash offer.";
                    return $this->createResponse();
                }
            } elseif ($data["offerType"] == 0) {
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "To make a trade offer please select a listing to trade or make a cash offer.";
                return $this->createResponse();
            }

            try {
                $this->tradeManager->addTrade($tradeData,$user);
                $this->httpStatusCode = 200;
                $this->apiResponse["message"] = "Trade offer sent successfully";
            } catch (\Exception $exception) {
                $this->httpStatusCode = 500;
                $this->apiResponse["message"] = "Something went wrong";

                if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                    $this->apiResponse["error_message"] = $exception->getMessage();
                } else {
                    error_log("Caught $exception");
                }
            }
        } else {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters sent";
        }

        return $this->createResponse();

    }

    public function deleteListingAction()
    {
        $data = $this->params()->fromPost();
        if (!isset($data["id"])) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No listing id provided";
            return $this->createResponse();
        }

        /**
         * @var $listing Listing
         */
        $listing = $this->entityManager->getRepository(Listing::class)->findOneBy(['id' => $data["id"]]);
        if ($listing == null) {
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No listing found for id: " . $data["id"];
            return $this->createResponse();
        } elseif ($listing->getStatus() == Listing::TRADED) {
            $this->httpStatusCode = 403;
            $this->apiResponse["message"] = "You can not delete traded listings";
            return $this->createResponse();
        }
        /**
         * @var User $user
         */
        $user = $this->currentUser();
        if ($user->getId() != $listing->getUser()->getId()) {
            $this->httpStatusCode = 403;
            $this->apiResponse["message"] = "You can only delete your own listings";
            return $this->createResponse();
        }


        try {
            $this->listingManager->deleteListing($listing);
            $this->httpStatusCode = 200;
            $this->apiResponse["message"] = "Listing deleted";
        } catch (\Exception $exception) {
            $this->httpStatusCode = 500;
            $this->apiResponse["message"] = "Something went wrong";

            if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                $this->apiResponse["error_message"] = $exception->getMessage();
            } else {
                error_log("Caught $exception");
            }
        }


        return $this->createResponse();

    }

    public function repostListingAction()
    {
        $data = $this->params()->fromPost();
        if (!isset($data["id"])) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No listing id provided";
            return $this->createResponse();
        }
        $currentDate = new \DateTime(date('Y-m-d H:i:s'));

        /**
         * @var $listing Listing
         */
        $listing = $this->entityManager->getRepository(Listing::class)->findOneBy(['id' => $data["id"]]);
        if ($listing == null) {
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No listing found for id: " . $data["id"];
            return $this->createResponse();
        } elseif (($listing->getStatus() == Listing::ACTIVE && $listing->getAvailability() >= $currentDate) || $listing->getStatus() == Listing::TRADED) {
            $this->httpStatusCode = 403;
            $this->apiResponse["message"] = "You can not repost active/traded listings";
            return $this->createResponse();
        }
        /**
         * @var User $user
         */
        $user = $this->currentUser();
        if ($user->getId() != $listing->getUser()->getId()) {
            $this->httpStatusCode = 403;
            $this->apiResponse["message"] = "You can only repost your own listings";
            return $this->createResponse();
        }


        try {
            $this->listingManager->repostListing($listing);
            $this->httpStatusCode = 200;
            $this->apiResponse["message"] = "Listing reposted";
            $this->apiResponse["data"] = new \DateTime(date('Y-m-d H:i:s', strtotime('+1 month')));
        } catch (\Exception $exception) {
            $this->httpStatusCode = 500;
            $this->apiResponse["message"] = "Something went wrong";

            if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                $this->apiResponse["error_message"] = $exception->getMessage();
            } else {
                error_log("Caught $exception");
            }
        }


        return $this->createResponse();

    }


    /**
     * Change page number from url
     *
     * @param $url
     * @param $page
     * @return string
     */
    private function changePageNo($url, $page)
    {
        if (strpos($url, 'page') !== false) {
            return preg_replace("/page=([+--0-9]*)/", 'page=' . $page, $url);
        } elseif (strpos($url, '?') !== false) {
            return $url . '&page=' . $page;
        } else {
            return $url . '?page=' . $page;
        }
    }

    /**
     * Get current url
     *
     * @return string
     */
    private function getCurrentUrl()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}
