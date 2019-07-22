<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function search($term)
    {
        $qb = $this->createQueryBuilder('tag')
            ->andWhere('tag.name LIKE :searchTerm');

        return $qb
            ->setParameter('searchTerm', '%'.$term.'%')
            ->getQuery()
            ->execute();
    }
}