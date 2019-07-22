<?php
namespace Application\Controller\Plugin;

use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceManager;

class IsDevClientPlugin extends AbstractPlugin
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     *
     * @return bool
     */
    public function __invoke()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $clientIp = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }

        $config = $this->getServiceManager()->get('config');

        if(isset($config['development_client']) and in_array($clientIp, $config['development_client'])){
            return true;
        }

        return false;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param mixed $serviceManager
     * @return $this
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}