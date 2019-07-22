<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class MessageStatusRepository extends EntityRepository
{
    public function updateStatusByConversationIdAndUserId($user, $conversation)
    {
        $query = $this->createQueryBuilder('ms');
        $query->update();
        $query->set('ms.status', 1);
        $query->where("ms.user = :user")
            ->setParameter("user", $user);
        $query->andWhere("ms.message IN (SELECT m.id FROM Application\Entity\Message as m WHERE m.conversation = :conversation)")
            ->setParameter("conversation", $conversation);
        $result = $query ->getQuery()->execute();

        return $result;
    }
}