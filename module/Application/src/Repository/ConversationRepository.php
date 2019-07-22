<?php

namespace Application\Repository;

use Application\Entity\MessageStatus;
use Application\Entity\User;
use Doctrine\ORM\EntityRepository;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Join;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class ConversationRepository extends EntityRepository
{
    public function checkIfUsersHasConversation($sender, $receiver, $config)
    {
        $subSelect = new Select("conversation_user");
        $subSelect->columns(["conversation_id" => "conversation_id", "participants" => new Expression("COUNT(user_id)")]);
        $subSelect->group("conversation_id");

        $select = new Select("conversation");
        $select->columns(["id"]);
        $select->join(["c1" => "conversation_user"],"conversation.id = c1.conversation_id",[]);
        $select->join(["c2" => "conversation_user"],"conversation.id = c2.conversation_id",[]);
        $select->join(["count_participants" => $subSelect],"conversation.id = count_participants.conversation_id",["participants"]);
        $select->where->equalTo("c1.user_id", $sender->getId())->and->equalTo("c2.user_id", $receiver->getId());
        $select->having("count_participants.participants = 2");

        $dbAdapter = new Adapter($config["db_settings"]);
        $sql = new Sql($dbAdapter);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();
    }

    public function checkIfUserIsInTheConversation(User $user,int $conversation_id)
    {
        $query = $this->createQueryBuilder('c');
        $query->select("c.id");
        $query->innerJoin("c.users","u");
        $query->where("u.id = :user")
            ->setParameter("user", $user);
        $query->andWhere("c.id = :conversation_id")
            ->setParameter("conversation_id", $conversation_id);
        $result = $query ->getQuery()->execute();

        return $result;
    }

    public function getConversationsByUser($user, $config, $limit=null)
    {
        $dbAdapter = new Adapter($config["db_settings"]);

        $subSelect = new Select("conversation");
        $subSelect->columns(["id"]);
        $subSelect->join( "conversation_user","conversation.id = conversation_user.conversation_id",[]);
        $subSelect->join(["message" =>  new Expression("(SELECT `message`.* FROM `message` ORDER BY `id` DESC LIMIT 9223372036854775807)")],"conversation.id = message.conversation_id",["user_id" => "user_id", "message_id" => "id","text" => "text", "date_added" => "date_added"]);
        $subSelect->join("message_status","message_status.message_id = message.id",["status"]);
        $subSelect->where->equalTo("conversation_user.user_id", $user->getId())->and->equalTo("message_status.user_id", $user->getId());
        $subSelect->group('id');
        $subSelect->order('date_added DESC');

        if ($limit){
            $subSelect->limit($limit);
        }

        $select = new Select(['t1' => $subSelect]);
        $select->join('conversation_user', 't1.id = conversation_user.conversation_id', [], Join::JOIN_LEFT);
        $select->join('user', 'conversation_user.user_id = user.id', ["first_name" => "first_name", "name" => "name", "photo" => "photo_url"], Join::JOIN_LEFT);
        $select->where->notEqualTo('conversation_user.user_id', $user->getId());
        $select->group('id');
        $select->order('message_id DESC');

        $sql = new Sql($dbAdapter);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();
    }

    public function getUnseenConversationsByUser($user, $config)
    {
        $dbAdapter = new Adapter($config["db_settings"]);

        $select = new Select("conversation");
        $select->columns(["id"]);
        $select->join( "conversation_user","conversation.id = conversation_user.conversation_id",[]);
        $select->join(["message" =>  new Expression("(SELECT `message`.* FROM `message` ORDER BY `id` DESC LIMIT 9223372036854775807)")],"conversation.id = message.conversation_id",["user_id" => "user_id", "message_id" => "id","text" => "text", "date_added" => "date_added"]);
        $select->join("user","message.user_id = user.id",["photo" => "photo_url", "first_name" => "first_name", "name" => "name"]);
        $select->join("message_status","message_status.message_id = message.id",["status"]);
        $select->where->equalTo("conversation_user.user_id", $user->getId())->and->equalTo("message_status.user_id", $user->getId())->and->equalTo("message_status.status", MessageStatus::NOT_SEEN);
        $select->group('id');
        $select->order('date_added DESC');

        $sql = new Sql($dbAdapter);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();
    }
}