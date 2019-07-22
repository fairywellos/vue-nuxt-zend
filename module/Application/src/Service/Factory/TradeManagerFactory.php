<?php

namespace Application\Service\Factory;


use Application\Service\NotificationManager;
use Application\Service\PusherManager;
use Application\Service\TradeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TradeManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $pusherManager = $container->get(PusherManager::class);
        $notificationManager = $container->get(NotificationManager::class);


        return new TradeManager($entityManager,$pusherManager,$notificationManager);
    }

}