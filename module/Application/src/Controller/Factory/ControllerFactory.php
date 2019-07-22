<?php

namespace Application\Controller\Factory;


use Application\Service\MailSmtp;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {


        $service = (null === $options) ? new $requestedName : new $requestedName($options);

        if(method_exists($service, 'setServiceManager')){
            $service->setServiceManager($container);
        }

        if(method_exists($service, 'setEntityManager')){
            $service->setEntityManager($container->get("doctrine.entitymanager.orm_default"));
        }

        if(method_exists($service, 'setMailSmtp')){
            $service->setMailSmtp($container->get(MailSmtp::class));
        }

        if(method_exists($service, 'setConfig')){
            $service->setConfig($container->get("config"));
        }

        return $service;
    }
}