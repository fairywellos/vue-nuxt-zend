<?php

namespace Application\Service\Factory;


use Application\Service\ListingManager;
use Application\Service\NotificationManager;
use Application\Service\PusherManager;
use Application\Service\UploadManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ListingManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ListingManager|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $uploadManager = $container->get(UploadManager::class);
        $pusherManager = $container->get(PusherManager::class);
        $notificationManager = $container->get(NotificationManager::class);


        return new ListingManager($entityManager,$uploadManager,$pusherManager,$notificationManager);
    }

}