<?php

namespace Application\Controller;

use Application\Entity\Listing;
use Application\Entity\Trade;
use Application\Entity\User;
use Application\Repository\TradeRepository;
use Application\Service\ListingManager;
use Application\Service\TradeManager;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;

/**
 * @method User currentUser()
 * Class TradeController
 * @package Application\Controller
 */
class TradeController extends ApiController
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

    private $config;

    public function __construct($listingManager,$entityManager,$tradeManager,$config)
    {
        $this->entityManager = $entityManager;
        $this->listingManager = $listingManager;
        $this->tradeManager = $tradeManager;
        $this->config = $config;
    }

    public function indexAction()
    {
        $data = $this->params()->fromQuery();

        if(empty($data["id"])){
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No trade id provided";
            return $this->createResponse();
        }
        /**
         * @var Trade $trade
         */
        $trade = $this->entityManager->getRepository(Trade::class)->findOneById($data["id"]);
        if($trade == null){
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No trade found for id: " . $data["id"];
            return $this->createResponse();
        }
        elseif($trade->getListing()->getUser()->getId() != $this->tokenPayload->id &&
                $trade->getTradeOffers()[0]->getUser()->getId() != $this->tokenPayload->id){

            $this->httpStatusCode = 403;
            $this->apiResponse["message"] = "You are not part of this trade";
            return $this->createResponse();
        }

        $this->httpStatusCode = 200;

        // Set the response
        $this->apiResponse['data'] = $trade->toArray(true);

        return $this->createResponse();
    }

    public function getListingTradesAction()
    {

        $data = $this->params()->fromQuery();

        if(empty($data["id"])){
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No listing id provided";
            return $this->createResponse();
        }

        /**
         * @var $listing Listing
         */
        $listing = $this->entityManager->getRepository(Listing::class)->findOneBy(['id' => $data["id"], 'user' => $this->tokenPayload->id]);

        if($listing == null){
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No listing found for id: " . $data["id"];
            return $this->createResponse();
        }

        $response_data = $listing->getTrades();

        $array_response = [];
        /**
         * @var Trade $item
         */
        foreach ($response_data as $item){
            $array_item = $item->toArray();
            if($item->getStatus() == Trade::PENDING){
                $array_response[$array_item["id"]] = $array_item;
            }
        }

        $this->httpStatusCode = 200;
        $this->apiResponse['data'] = $array_response;
        return $this->createResponse();

    }

    public function getUserTradeOffersAction()
    {

        /**
         * @var $user User
         */
        $user =  $this->currentUser();

        /**
         * @var $tradeRepository TradeRepository
         */
        $tradeRepository = $this->entityManager->getRepository(Trade::class);
        $tradeOffers = $tradeRepository->getUserTradeOffers($user,$this->config);

        $this->httpStatusCode = 200;
        $this->apiResponse['data'] = $tradeOffers;
        return $this->createResponse();
    }

    public function getUserAcceptedTradesAction()
    {

        /**
         * @var $user User
         */
        $user =  $this->currentUser();

        /**
         * @var $tradeRepository TradeRepository
         */
        $tradeRepository = $this->entityManager->getRepository(Trade::class);
        $tradeOffers = $tradeRepository->getUserAcceptedTrades($user,$this->config);

        $this->httpStatusCode = 200;
        $this->apiResponse['data'] = $tradeOffers;
        return $this->createResponse();
    }

    public function changeStatusAction()
    {
        $data = $this->params()->fromPost();
        if(isset($data["tradeId"]) && isset($data["status"])){
            /**
             * @var $user User
             */
            $user =  $this->currentUser();
            /**
             * @var Trade $trade
             */
            $trade = $this->entityManager->getRepository(Trade::class)->findOneById($data["tradeId"]);
            if($trade == null){
                $this->httpStatusCode = 404;
                $this->apiResponse["message"] = "No trade found for id: " . $data["tradeId"];
                return $this->createResponse();
            }
            if($trade->getListing()->getUser()->getId() !== $user->getId()){
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = "You are not the owner of the trade";
                return $this->createResponse();
            }
            if($trade->getStatus() !== Trade::PENDING){
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = "Trade offer status can not be change any more.";
                return $this->createResponse();
            }

            try{
                $message = $this->tradeManager->updateStatus($trade,$data["status"],$user);
                $this->httpStatusCode = 200;
                $this->apiResponse["message"] = $message;
            }catch (\Exception $exception){
                $this->httpStatusCode = 500;
                $this->apiResponse["message"] = "Something went wrong";
                if($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()){
                    $this->apiResponse["error_message"] = $exception->getMessage();
                }
                return $this->createResponse();
            }

        }else{
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters sent";
        }

        return $this->createResponse();

    }

}