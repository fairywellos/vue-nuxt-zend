<?php

namespace Application\Controller;

use Application\Entity\Conversation;
use Application\Entity\Listing;
use Application\Entity\MessageStatus;
use Application\Entity\Notification;
use Application\Entity\User;
use Application\Repository\ConversationRepository;
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
class NotificationController extends ApiController
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
        $user =  $this->currentUser();
        $notifications = $this->entityManager->getRepository(Notification::class)->findByUser($user, ['dateAdded' => 'DESC'], 8);
        $count = $this->entityManager->getRepository(Notification::class)->findBy(array('status' => 2, 'user' => $user));

        $this->httpStatusCode = 200;
        $this->apiResponse['unseen'] = count($count);
        $this->apiResponse['notifications'] = $notifications;
        return $this->createResponse();
    }

    public function getAllAction()
    {
        $user = $this->currentUser();
        $notifications = $this->entityManager->getRepository(Notification::class)->findBy(['user' => $user], ['dateAdded' => 'DESC']);
        $count = $this->entityManager->getRepository(Notification::class)->findBy(array('status' => 2, 'user' => $user));

        $this->httpStatusCode = 200;
        $this->apiResponse['unseen'] = count($count);
        $this->apiResponse['notifications'] = $notifications;
        return $this->createResponse();
    }

    public function updateStatusAction()
    {
        if($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            //check if all parameters are given
            if (empty($data['notification_id'])) {
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "Invalid parameters given";
                return $this->createResponse();
            }

            /**
             * @var NotificationRepository $notificationRepo
             */
            $notificationRepo = $this->entityManager->getRepository(Notification::class);
            $user = $this->currentUser();

            $notification = $notificationRepo->findOneById($data['notification_id']);

            if($notification == null){
                $this->httpStatusCode = 404;
                $this->apiResponse["message"] = "No notification found for id: " . $data["notification_id"];
                return $this->createResponse();
            }

            $check = $notificationRepo->updateStatusByNotificationIdAndUserId($user, $notification);

            if (!$check) {
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = "You cannot change status for this notification";
                return $this->createResponse();
            }

            $this->httpStatusCode = 200;
            $this->apiResponse['message'] = "success update of status";
        }

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
