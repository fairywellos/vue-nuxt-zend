<?php

namespace Application\Service;

use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Pusher\Pusher;

class PusherManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    private $pusher;

    public function __construct($entityManager, $pusher_options)
    {
        try{
            $this->pusher = new Pusher(
                $pusher_options['auth_key'],
                $pusher_options['secret'],
                $pusher_options['app_id'],
                [
                    'cluster' => 'eu',
                    'useTLS' => true
                ]
            );
        } catch (\Exception $exception){
            deg("error " . $exception->getMessage());
        }

        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @param array $data
     */
    public function sendMessage($user, $data)
    {
        try{
            $this->pusher->trigger('user-channel-' . $user->getId(), 'message-event', $data);
        } catch (\Exception $exception){
            deg("error " . $exception->getMessage());
        }
    }

    /**
     * @param User $user
     * @param array $data
     */
    public function sendNotification($user, $data)
    {
        try{
            $this->pusher->trigger('user-channel-' . $user->getId(), 'notification-event', $data);
        } catch (\Exception $exception){
            deg("error " . $exception->getMessage());
        }
    }
}