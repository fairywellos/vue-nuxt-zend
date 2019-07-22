<?php

namespace Application\Controller\Factory;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PluginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = (null === $options) ? new $requestedName : new $requestedName($options);

        if(method_exists($service, 'setServiceManager')){
            $service->setServiceManager($container);
        }

        if(method_exists($service, 'setEntityManager')){
            $service->setEntityManager($container->get("doctrine.entitymanager.orm_default"));
        }

        return $service;
    }
}