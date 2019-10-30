<?php

namespace Application\Controller;

use Application\Entity\EtsyTrackingListings;
use Application\Entity\EtsyTrackingUsers;
use Application\Entity\Listing;
use Application\Entity\TrackingRequests;
use Application\Entity\User;
use Application\Repository\TrackingRequestRepository;
use Application\Service\EtsyManager;
use Application\Service\ListingManager;
use Application\Service\UploadManager;
use Application\Service\UserManager;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class EtsyController
 *
 * @package Application\Controller
 */
class EtsyController extends ApiController
{
    /**
     * Entity manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function getActiveAction()
    {
        $data = $this->params()->fromQuery();

        /**
         * @var EtsyManager $etsyManager
         */
        $etsyManager = $this->getServiceManager()->get(EtsyManager::class);
        $trackingRequestRepo = $this->entityManager->getRepository(TrackingRequests::class);

        if ($trackingRequestRepo->checkIfExceededEtsyRequestLimit('etsy')){
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "Exceeded Etsy Request limit!";
            return $this->createResponse();
        }

        $response = $etsyManager->sendRequest([
            "route" => "listings/active",
             "params" => [
                 "geo_level" => "country",
                 "location" => "US"
             ]
        ]);

        $trackingRequestRepo->addRequest('etsy', 1);

        if (!is_array($response)){
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "Bad params for this esty request!";
            return $this->createResponse();
        }

        foreach ($response["results"] as $key => $etsy_listing) {
            $start_time = microtime(true);
            $etsyManager->addEtsyListing($etsy_listing);
            $end_time = microtime(true);

            if(($end_time-$start_time) <= 1) {
                sleep(1);
            }
        }

        $this->httpStatusCode = 200;
        $this->apiResponse['message'] = "success";
        return $this->createResponse();
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
