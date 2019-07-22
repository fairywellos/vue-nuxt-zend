<?php

namespace Application\Controller;

use Application\Entity\Conversation;
use Application\Entity\MessageStatus;
use Application\Entity\User;
use Application\Repository\ConversationRepository;
use Application\Repository\MessageStatusRepository;
use Application\Service\ConversationManager;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ConversationController
 *
 * @package Application\Controller
 */
class ConversationController extends ApiController
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
        $data = $this->params()->fromQuery();

        //check if all parameters are given
        if(empty($data['conversation_id'])){
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters given";
            return $this->createResponse();
        }

        /**
         * @var ConversationRepository $conversationRepo
         */
        $conversationRepo = $this->entityManager->getRepository(Conversation::class);
        $user = $this->currentUser();

        /**
         * @var Conversation $conversation
         */
        $conversation = $conversationRepo->findOneById($data['conversation_id']);

        if($conversation == null){
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No conversation found for id: " . $data["conversation_id"];
            return $this->createResponse();
        }

        if(empty($conversationRepo->checkIfUserIsInTheConversation($user, $data['conversation_id']))){
            $this->httpStatusCode = 403;
            $this->apiResponse["message"] = "You are not authorized to see this conversation";
            return $this->createResponse();
        }

        /**
         * @var MessageStatusRepository $messageStatusRepo
         */
        $messageStatusRepo = $this->entityManager->getRepository(MessageStatus::class);

        $messageStatusRepo->updateStatusByConversationIdAndUserId($user, $conversation);

        $this->httpStatusCode = 200;
        $this->apiResponse = $conversation->toArray();
        return $this->createResponse();
    }

    public function addMessageAction()
    {
        if($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();

            //check if all parameters are given
            if(empty($data["message"]) || (empty($data['receiver_id']) && empty($data['conversation_id']))){
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "Invalid parameters given";
                return $this->createResponse();
            }

            /**
             * @var ConversationManager $conversationManager
             */
            $conversationManager = $this->getServiceManager()->get(ConversationManager::class);

            /**
             * @var ConversationRepository $conversationRepo
             */
            $conversationRepo = $this->entityManager->getRepository(Conversation::class);

            $data['sender'] = $this->currentUser();

            //check if api has received a conversation id
            if(empty($data['conversation_id'])){
                $data['receiver'] = $this->entityManager->getRepository(User::class)->findOneById($data['receiver_id']);

                //check if the receiver is not the sender
                if($data['receiver']->getId() !== $data['sender']->getId()){
                    $check = $conversationRepo->checkIfUsersHasConversation($data['sender'], $data['receiver'], $this->getServiceManager()->get("config"));
                    if(!empty($check)){
                        $conversation_id = $check[0]['id'];
                    } else {
                       $conversation_id = null;
                    }
                } else {
                    $this->httpStatusCode = 400;
                    $this->apiResponse["message"] = "You can not send a message to yourself.";
                    return $this->createResponse();
                }

            } else {
                $conversation_id = $data['conversation_id'];
                $conversation = $conversationRepo->findOneById($conversation_id);

                if($conversation == null){
                    $this->httpStatusCode = 404;
                    $this->apiResponse["message"] = "No conversation found for id: " . $data["id"];
                    return $this->createResponse();
                }

                if(empty($conversationRepo->checkIfUserIsInTheConversation($data['sender'], $conversation_id))){
                    $this->httpStatusCode = 403;
                    $this->apiResponse["message"] = "You are not authorized to send a message to this conversation";
                    return $this->createResponse();
                }
            }

            try{
                $message = $conversationManager->addMessage($data, $conversation_id);
            }catch (\Exception $exception){
                $this->httpStatusCode = 500;
                $this->apiResponse["message"] = "Something went wrong";
                if($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()){
                    $this->apiResponse["error_message"] = $exception->getMessage();
                }
                return $this->createResponse();
            }

            $this->httpStatusCode = 200;
            $this->apiResponse = $message;
        }

        return $this->createResponse();
    }

    public function getAllAction()
    {
        $user = $this->currentUser();
        $conversation = $this->entityManager->getRepository(Conversation::class)->getConversationsByUser($user, $this->getServiceManager()->get("config"));

        $this->httpStatusCode = 200;
        $this->apiResponse = $conversation;
        return $this->createResponse();
    }

    public function getNotificationConversationsAction()
    {
        $user = $this->currentUser();
        $conversations = $this->entityManager->getRepository(Conversation::class)->getConversationsByUser($user, $this->getServiceManager()->get("config"), 10);
        $new_conversations = count($this->entityManager->getRepository(Conversation::class)->getUnseenConversationsByUser($user, $this->getServiceManager()->get("config")));

        $this->httpStatusCode = 200;
        $this->apiResponse['conversations'] = $conversations;
        $this->apiResponse['new_conversations'] = $new_conversations;
        return $this->createResponse();
    }

    public function updateStatusAction()
    {
        if($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            //check if all parameters are given
            if(empty($data['conversation_id'])){
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "Invalid parameters given";
                return $this->createResponse();
            }

            /**
             * @var ConversationRepository $conversationRepo
             */
            $conversationRepo = $this->entityManager->getRepository(Conversation::class);
            $user = $this->currentUser();

            $conversation = $conversationRepo->findOneById($data['conversation_id']);

            if($conversation == null){
                $this->httpStatusCode = 404;
                $this->apiResponse["message"] = "No conversation found for id: " . $data["conversation_id"];
                return $this->createResponse();
            }

            if(empty($conversationRepo->checkIfUserIsInTheConversation($user, $data['conversation_id']))){
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = "You are not authorized to see this conversation";
                return $this->createResponse();
            }

            /**
             * @var MessageStatusRepository $messageStatusRepo
             */
            $messageStatusRepo = $this->entityManager->getRepository(MessageStatus::class);

            $messageStatusRepo->updateStatusByConversationIdAndUserId($user, $conversation);

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
