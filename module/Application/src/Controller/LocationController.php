<?php

namespace Application\Controller;


use Application\Entity\Location;
use Application\Repository\LocationRepository;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\ServiceManager\ServiceManager;


class LocationController extends ApiController
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
        $locations = $this->getEntityManager()->getRepository(Location::class)->getAllLocations();

        $this->httpStatusCode = 200;
        $this->apiResponse = $locations;
        return $this->createResponse();
    }

    public function getlocationAction()
    {
        $id = $this->params()->fromQuery("id",false);

        if(!$id){
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No location id provided";
            return $this->createResponse();
        }

        /**
         * @var LocationRepository $locationRepo;
         */
        $locationRepo = $this->getEntityManager()->getRepository(Location::class);
        $location = $locationRepo->findOneBy(["id" => $id]);
        if($location == null){
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No location found for id: " . $id;
            return $this->createResponse();
        }

        $this->httpStatusCode = 200;
        $this->apiResponse = $location->toArray();
        return $this->createResponse();
    }


    public function searchAction()
    {
        $query = $this->params()->fromQuery('query',false);
        $coords = $this->params()->fromQuery('coords',false);
        $latitude = $this->params()->fromQuery('latitude',false);
        $longitude = $this->params()->fromQuery('longitude',false);
        /**
         * @var LocationRepository $locationRepo;
         */
        $locationRepo = $this->getEntityManager()->getRepository(Location::class);
        if($query){
            if(intval($query)){
                $locations = $locationRepo->findLocationByZipCode($query);
            }else{
                $filters = explode(",",$query);
                $city = trim($filters[0]);
                $state = trim($filters[1] ?? false);
                $locations = $locationRepo->findLocationByCityAndState($city,$state);
            }
        }elseif ($coords){
            if(!($latitude && $longitude)){
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "Invalid parameters given";
                return $this->createResponse();
            }

            $locations = $locationRepo->getLocationByCoordinates((float)$latitude, (float)$longitude, $this->getServiceManager()->get("config"));

            $this->httpStatusCode = 200;
            $locations = $locations[0];
        }
        else{
            $locations = $locationRepo->getAllLocations();
        }


        $this->httpStatusCode = 200;
        $this->apiResponse = $locations;
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