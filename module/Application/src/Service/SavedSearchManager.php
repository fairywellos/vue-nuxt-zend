<?php

namespace Application\Service;


use Application\Entity\SavedSearch;
use Application\Entity\User;
use Doctrine\ORM\EntityManager;

class SavedSearchManager
{

    /**
     * Entity manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param string $query
     * @param User $user
     * @param bool $status
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setSaved($query, $user, $status){
        $query = $this->sortQuery($query);
        $savedSearch = $this->entityManager->getRepository(SavedSearch::class)->findOneBy(['query' => $query]);
        if(empty($savedSearch)){
            $savedSearch = new SavedSearch();
            $savedSearch->setQuery($query);
        }

        if((int)$status === SavedSearch::ADD_SAVED){
            $user->addUserSavedSearch($savedSearch);
        }else{
            $user->removeUserSavedSearch($savedSearch);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function sortQuery($queryStr){
        parse_str($queryStr, $queryArr);
        ksort($queryArr);

        return http_build_query($queryArr);
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