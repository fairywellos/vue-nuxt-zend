<?php

namespace Application\Repository;

use Application\Entity\MessageStatus;
use Application\Entity\User;
use Doctrine\ORM\EntityRepository;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class NotificationRepository extends EntityRepository
{
    public function updateStatusByNotificationIdAndUserId($user, $notification)
    {
        $query = $this->createQueryBuilder('n');
        $query->update();
        $query->set('n.status', 1);
        $query->where("n.user = :user")
            ->setParameter("user", $user);
        $query->andWhere("n.id = :notification")
            ->setParameter("notification", $notification);
        $result = $query ->getQuery()->execute();

        return $result;
    }
}