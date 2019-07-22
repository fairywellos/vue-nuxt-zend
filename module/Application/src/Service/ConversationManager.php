<?php

namespace Application\Service;

use Application\Entity\Conversation;
use Application\Entity\Message;
use Application\Entity\MessageStatus;
use Doctrine\ORM\EntityManager;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\ServiceManager\ServiceManager;

class ConversationManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @param $data
     * @param null $conversation_id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return array
     */
    public function addMessage($data, $conversation_id = null)
    {
        $currentDate =  new \DateTime(date('Y-m-d H:i:s'));

        if(empty($conversation_id)) {
            $conversation = new Conversation();
            $conversation->addUser($data['sender']);
            $conversation->addUser($data['receiver']);
            $conversation->setDateAdded($currentDate);
            $conversation->setDateModified($currentDate);
        } else {
            $conversation = $this->entityManager->getRepository(Conversation::class)->findOneById($conversation_id);
            $conversation->setDateModified($currentDate);
        }

        $message = new Message();
        $message->setText($this->filterMessage($data['message']));
        $message->setUser($data['sender']);
        $message->setDateAdded($currentDate);
        $message->setDateModified($currentDate);

        $users = $conversation->getUsers();

        foreach ($users as $user){
            $messageStatus = new MessageStatus();
            $messageStatus->setUser($user);
            $messageStatus->setDateAdded($currentDate);
            $messageStatus->setDateModified($currentDate);

            if($user->getId() !== $data['sender']->getId()){
                $messageStatus->setStatus(MessageStatus::NOT_SEEN);
            } else {
                $messageStatus->setStatus(MessageStatus::SEEN);
            }
            $message->addMessageStatus($messageStatus);
        }

        $conversation->addMessage($message);

        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        $temp_message = array(
            "conversation" => $conversation->getId(),
            "dateAdded"    => $conversation->getDateAdded(),
            "dateModified"    => $conversation->getDateModified(),
            "id" => $message->getId(),
            "text" => $message->getText(),
            "user" => array(
                "photo" => $data['sender']->getPhotoUrl(),
                "publicName" => $data['sender']->getPublicName(),
                "user_id" => $data['sender']->getId(),
            )
        );

        foreach ($message->getMessageStatuses() as $status) {
            $temp_message['messageStatuses'][] = $status->toArray();
        }

        /**
         * @var PusherManager $pusherManager
         */
        $pusherManager = $this->getServiceManager()->get(PusherManager::class);

        foreach ($users as $user){
            if($user->getId() !== $data['sender']->getId()){
                $pusherManager->sendMessage($user, $temp_message);
            }
        }

        return $temp_message;
    }

    private function filterMessage($message)
    {
        $stripTasgs = new StripTags();
        $message = $stripTasgs->filter($message);

        $stringTrim = new StringTrim();
        $message = $stringTrim->filter($message);

        return $message;
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