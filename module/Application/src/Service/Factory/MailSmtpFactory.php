<?php

namespace Application\Service\Factory;

use Application\Service\MailSmtp;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MailSmtpFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return MailSmtp|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $renderer = $container->get('Zend\View\Renderer\RendererInterface');
        $config = $container->get('config');

        return new MailSmtp($entityManager,$config,$renderer);
    }

}