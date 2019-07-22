<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = (null === $options) ? new $requestedName : new $requestedName($options);

        if(method_exists($service, 'setServiceManager')){
            $service->setServiceManager($container);
        }

        if(method_exists($service, 'setEntityManager')){
            $service->setEntityManager($container->get('doctrine.entitymanager.orm_default'));
        }

        return $service;
    }
}