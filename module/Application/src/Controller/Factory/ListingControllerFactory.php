<?php

namespace Application\Controller\Factory;


use Application\Controller\ListingController;
use Application\Service\ListingManager;
use Application\Service\NotificationManager;
use Application\Service\PusherManager;
use Application\Service\TradeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ListingControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get("doctrine.entitymanager.orm_default");
        $listingManager = $container->get(ListingManager::class);
        $tradeManager = $container->get(TradeManager::class);
        $pusherManager = $container->get(PusherManager::class);
        $notificationManager = $container->get(NotificationManager::class);

        return new ListingController($listingManager,$entityManager,$tradeManager,$pusherManager,$notificationManager);
    }
}