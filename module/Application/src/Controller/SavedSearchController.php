<?php

namespace Application\Controller;

use Application\Entity\Location;
use Application\Entity\SavedSearch;
use Application\Entity\User;
use Application\Form\SetSavedSearchForm;
use Application\Repository\LocationRepository;
use Application\Service\SavedSearchManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\Cache\Storage\Plugin\Serializer;
use Zend\Db\ResultSet\ResultSet;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayObject;

/**
 * Class SavedSearchController
 *
 * @method User currentUser()
 * @method Plugin\IsDevClientPlugin isDevClient()
 * @package Application\Controller
 */
class SavedSearchController extends ApiController
{
    /**
     * Entity manager
     * @var EntityManager
     */
    private $entityManager;

    private $serviceManager;

    public function indexAction()
    {
        $savedSearches = $this->currentUser()->getUserSavedSearches();

        if(!empty($savedSearches)){
            foreach ($savedSearches as $savedSearch){
                /**
                 * @var $savedSearch SavedSearch
                 */
                parse_str($savedSearch->getQuery(), $queryArr);

                if(!empty($queryArr["location"])){

                    /**
                     * @var LocationRepository $locationRepo;
                     */
                    $locationRepo = $this->getEntityManager()->getRepository(Location::class);
                    $location = $locationRepo->findOneBy(["id" => $queryArr["location"]]);
                    $queryArr["locationText"] = $location->getLocationText();
                }

                $this->apiResponse['data'][] = $queryArr;
            }
        }

        $this->httpStatusCode = 200;
        return $this->createResponse();
    }

    public function setSavedAction()
    {
        $form = new SetSavedSearchForm();

        if($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form
            if($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                try{
                    /**
                     * @var $savedSearchManager SavedSearchManager
                     */
                    $savedSearchManager = $this->getServiceManager()->get(SavedSearchManager::class);
                    $savedSearchManager->setSaved($data['query'], $this->currentUser(), $data['status']);

                    $this->httpStatusCode = 200;
                    if((int)$data['status'] === SavedSearch::ADD_SAVED){
                        $this->apiResponse["message"] = "Search added successfully";
                    }else{
                        $this->apiResponse["message"] = "Search removed successfully";
                    }
                }catch (\Exception $exception){
                    $this->httpStatusCode = 500;
                    $this->apiResponse["message"] = "Something went wrong";

                    if($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()){
                        $this->apiResponse["error_message"] = $exception->getMessage();
                    }else{
                        error_log("Caught $exception");
                    }
                }
            }else{
                $this->httpStatusCode = 400;
                $this->apiResponse = $form->getMessages();
            }
        }

        return $this->createResponse();
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
}
