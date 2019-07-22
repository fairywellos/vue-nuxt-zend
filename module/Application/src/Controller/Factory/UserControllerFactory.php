<?php

namespace Application\Controller\Factory;


use Application\Controller\UserController;
use Application\Service\RatingManager;
use Application\Service\UploadManager;
use Application\Service\UserManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get("doctrine.entitymanager.orm_default");
        $userManager   = $container->get(UserManager::class);
        $uploadManager = $container->get(UploadManager::class);
        $ratingManager = $container->get(RatingManager::class);
        $config        = $container->get('config');

        return new UserController($entityManager,$userManager,$ratingManager,$uploadManager,$config);
    }
}