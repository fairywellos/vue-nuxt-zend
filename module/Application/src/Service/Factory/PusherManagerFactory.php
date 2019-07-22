<?php

namespace Application\Service\Factory;


use Application\Service\ConversationManager;
use Application\Service\PusherManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PusherManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('config');

        return new PusherManager($entityManager, $config['pusher_options']);
    }
}