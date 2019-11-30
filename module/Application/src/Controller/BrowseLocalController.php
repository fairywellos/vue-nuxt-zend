<?php

namespace Application\Controller;

use Application\Entity\Conversation;
use Application\Entity\Listing;
use Application\Entity\Location;
use Application\Entity\MessageStatus;
use Application\Entity\Notification;
use Application\Entity\User;
use Application\Repository\ConversationRepository;
use Application\Repository\LocationRepository;
use Application\Repository\MessageStatusRepository;
use Application\Repository\NotificationRepository;
use Application\Service\ConversationManager;
use Application\Service\NotificationManager;
use Application\Service\PusherManager;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class NotificationController
 *
 * @package Application\Controller
 */
class BrowseLocalController extends ApiController
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

    public function indexAction()
    {
        if($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            //check if all parameters are given
            if(empty($data['lat']) || empty($data['long'])){
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "Invalid parameters given";
                return $this->createResponse();
            }

            /**
             * @var LocationRepository $locationRepo
             */
            $locationRepo = $this->entityManager->getRepository(Location::class);

            $location = $locationRepo->getLocationByCoordinates((float)$data['lat'], (float)$data['long'], $this->getServiceManager()->get("config"));
            $this->httpStatusCode = 200;
            $this->apiResponse = $location[0];
            return $this->createResponse();
        }
        
        $this->httpStatusCode = 404;
        $this->apiResponse['message'] = "error";
        return $this->createResponse();
    }
    
    public function getAllAction()
    {
        $params = $this->params()->fromQuery();
        
        
        //check if all parameters are given
        if(empty($params['location'])){
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters given";
            return $this->createResponse();
        }
        
        $listingRepo = $this->entityManager->getRepository(Listing::class);
        
        $params['fields'] = 'id,title,description,main_photo,price,location,user_name,user_id,user_photo,saved,trade_type';
        $listings = $listingRepo->filterBy($params);
        // var_dump("===========getAllAction=======", $listings);
        
        $this->httpStatusCode = 200;
        $this->apiResponse = $listings;
        return $this->createResponse();
    }

    public function getDefaultAction()
    {
        $params = $this->params()->fromQuery();

        //check if all parameters are given
        if(empty($params['limit'])){
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters given";
            return $this->createResponse();
        }

        $listingRepo = $this->entityManager->getRepository(Listing::class);

        $params['fields'] = 'id,title,description,main_photo,price,location,user_name,user_id,user_photo,sample_account,saved,trade_type';

        $listings = $listingRepo->filterBy($params);

        $this->httpStatusCode = 200;
        $this->apiResponse = $listings;
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
