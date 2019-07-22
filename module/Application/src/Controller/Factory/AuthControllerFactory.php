<?php

namespace Application\Controller\Factory;


use Application\Controller\AuthController;
use Application\Service\MailSmtp;
use Application\Service\UserManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get("doctrine.entitymanager.orm_default");
        $userManager = $container->get(UserManager::class);
        $mailSmtp = $container->get(MailSmtp::class);
        $config = $container->get('config');

        return new AuthController($entityManager,$userManager,$config,$mailSmtp);
    }
}