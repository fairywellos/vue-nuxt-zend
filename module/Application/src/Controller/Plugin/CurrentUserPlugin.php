<?php
namespace Application\Controller\Plugin;

use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class CurrentUserPlugin extends AbstractPlugin
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @return User
     */
    public function __invoke()
    {
        /**
         * @var $user User
         * @return User
         */
        $user = $this->getEntityManager()->getRepository(User::class)->find($this->getUserId()) ?? new User();

        return $user;
    }

    /**
     *
     * @return int
     */
    private function getUserId(){
        return $this->controller->tokenPayload->id ?? -1;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}