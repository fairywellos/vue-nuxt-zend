<?php

namespace Application\Controller\Factory;

use Application\Controller\TradeController;
use Application\Service\ListingManager;
use Application\Service\TradeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TradeControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get("doctrine.entitymanager.orm_default");
        $listingManager = $container->get(ListingManager::class);
        $tradeManager = $container->get(TradeManager::class);
        $config = $container->get("Config");

        return new TradeController($listingManager,$entityManager,$tradeManager,$config);
    }
}