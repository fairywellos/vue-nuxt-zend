<?php

namespace Application\Repository;

use Application\Entity\User;
use Doctrine\ORM\EntityRepository;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Join;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class UserRepository extends EntityRepository
{
    public function getUsersByFilters($params, $user_id, $config)
    {
        $subSelect1 = new Select("user");
        $subSelect1->columns([
            'id' => 'id',
            'first_name' => 'first_name',
            'name' => 'name',
            'email' => 'email',
            'username' => 'username',
            'privacy' => 'privacy',
            'photo_url' => 'photo_url',
            'rating' => 'rating',
            'location_id' => 'location_id',
            'trades' => new Expression('SUM(IF(trade.status = 2, 1, 0))')
        ]);
        $subSelect1->join('listing', 'user.id = listing.user_id', [], Join::JOIN_LEFT);
        $subSelect1->join('trade', 'listing.id = trade.listing_id', [], Join::JOIN_LEFT);
        $subSelect1->where->notEqualTo('public', 0);
        $subSelect1->where->EqualTo('user.status', 2);
        $subSelect1->group('id');

        $subSelect2 = new Select("user");
        $subSelect2->columns([
            'id' => 'id',
            'first_name' => 'first_name',
            'name' => 'name',
            'email' => 'email',
            'username' => 'username',
            'privacy' => 'privacy',
            'photo_url' => 'photo_url',
            'rating' => 'rating',
            'location_id' => 'location_id',
            'trades' => new Expression('SUM(IF(trade.status = 2, 1, 0))')
        ]);
        $subSelect2->join('trade_offer', 'user.id = trade_offer.user_id', [], Join::JOIN_LEFT);
        $subSelect2->join('trade', ' trade_offer.trade_id = trade.id', [], Join::JOIN_LEFT);
        $subSelect2->where->notEqualTo('public', 0);
        $subSelect2->where->EqualTo('user.status', 2);
        $subSelect2->group('id');

        $subSelect1->combine($subSelect2);


        $subSelect3 = new Select(['t1' => $subSelect1]);
        $subSelect3->columns([
            "id" => "id",
            "name" => new Expression("CONCAT(t1.first_name, ' ', t1.name)"),
            "email" => "email",
            'username' => 'username',
            'privacy' => 'privacy',
            'photo_url' => 'photo_url',
            'rating' => 'rating',
            "location_id" => "location_id",
            "trades" => new Expression("SUM(t1.trades)")
        ]);
        $subSelect3->where->notEqualTo('id', $user_id);
        $subSelect3->group('id');

        $subSelect4 = new Select(['t2' => $subSelect3]);
        $subSelect4->join(
            'following',
            't2.id = following.user_id',
            ["followers" => new Expression("COUNT(follower_id)")],
            Join::JOIN_LEFT
        );
        $subSelect4->group('id');

        $subSelect5 = new Select(['t3' => $subSelect4]);
        $subSelect5->join(
            'following',
            't3.id = following.user_id',
            ['followed' => new Expression("IF(follower_id = ?, 1, 0)", [$user_id])],
            Join::JOIN_LEFT
        );
        $subSelect5->order('followed DESC');
        $subSelect5->limit(9223372036854775807);

        $select = new Select(['t4' => $subSelect5]);
        $select->join('listing', 't4.id = listing.user_id', [], Join::JOIN_LEFT);

        if (!empty($params['main_category_id'])){
            $select->where->equalTo('listing.status', 1)
                ->and->greaterThanOrEqualTo('listing.availability', date('Y-m-d H:i:s'))
                ->and->in("listing.main_category_id", $params['main_category_id']);
        }

        if (!empty($params['selected_price_range']) && $params['selected_price_range'][1] != 0) {
            $select->where->between('listing.price', $params['selected_price_range'][0], $params['selected_price_range'][1])
                ->and->greaterThanOrEqualTo('listing.availability', date('Y-m-d H:i:s'));
        }

        if (!empty($params['search'])) {
            $select
                ->where
                ->nest()
                ->like('t4.name', "%" . $params['search'] . "%")
                ->or
                ->like("t4.email", "%" . $params['search'] . "%");
        }

        if (!empty($params['location_id'])) {
            $select->where->equalTo('t4.location_id', $params['location_id']);
        }

        $select->group('t4.id');
        $select->order('t4.trades DESC');

        if (!empty($params['offset'])) {
            $select->limit($params['offset']);
        } else {
            $select->limit(9223372036854775807);
        }


        $dbAdapter = new Adapter($config["db_settings"]);
        $sql = new Sql($dbAdapter);
//        deg($select->getSqlString($dbAdapter->getPlatform()),1);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();
    }

    public function getTradePartners($user_id, $config)
    {
        $subSelect1 = new Select("user");
        $subSelect1->columns([
            'id' => 'id',
            'first_name' => 'first_name',
            'name' => 'name',
            'email' => 'email',
            'username' => 'username',
            'privacy' => 'privacy',
            'photo_url' => 'photo_url',
            'rating' => 'rating',
            'location_id' => 'location_id',
            'trades' => new Expression('SUM(IF(trade.status = 2, 1, 0))')
        ]);
        $subSelect1->join('listing', 'user.id = listing.user_id', [], Join::JOIN_LEFT);
        $subSelect1->join('trade', 'listing.id = trade.listing_id', [], Join::JOIN_LEFT);
        $subSelect1->where->notEqualTo('public', 0);
        $subSelect1->where->EqualTo('user.status', 2);
        $subSelect1->group('id');

        $subSelect2 = new Select("user");
        $subSelect2->columns([
            'id' => 'id',
            'first_name' => 'first_name',
            'name' => 'name',
            'email' => 'email',
            'username' => 'username',
            'privacy' => 'privacy',
            'photo_url' => 'photo_url',
            'rating' => 'rating',
            'location_id' => 'location_id',
            'trades' => new Expression('SUM(IF(trade.status = 2, 1, 0))')
        ]);
        $subSelect2->join('trade_offer', 'user.id = trade_offer.user_id', [], Join::JOIN_LEFT);
        $subSelect2->join('trade', ' trade_offer.trade_id = trade.id', [], Join::JOIN_LEFT);
        $subSelect2->where->notEqualTo('public', 0);
        $subSelect2->where->EqualTo('user.status', 2);
        $subSelect2->group('id');

        $subSelect1->combine($subSelect2);


        $subSelect3 = new Select(['t1' => $subSelect1]);
        $subSelect3->columns([
            "id" => "id",
            "name" => new Expression("CONCAT(t1.first_name, ' ', t1.name)"),
            "email" => "email",
            "location_id" => "location_id",
            "trades" => new Expression("SUM(t1.trades)"),
            'username' => 'username',
            'privacy' => 'privacy',
            'photo_url' => 'photo_url',
            'rating' => 'rating',
        ]);
        $subSelect3->where->notEqualTo('id', $user_id);
        $subSelect3->group('id');

        $subSelect4 = new Select(['t2' => $subSelect3]);
        $subSelect4->join(
            'following',
            't2.id = following.user_id',
            ["followers" => new Expression("COUNT(follower_id)")],
            Join::JOIN_LEFT
        );
        $subSelect4->group('id');

        $subSelect5 = new Select(['t3' => $subSelect4]);
        $subSelect5->join(
            'following',
            't3.id = following.user_id',
            ['followed' => new Expression("IF(follower_id = ?, 1, 0)", [$user_id])],
            Join::JOIN_LEFT
        );
        $subSelect5->order('followed DESC');
        $subSelect5->limit(9223372036854775807);

        $select = new Select(['t4' => $subSelect5]);

        $select->where->equalTo('followed', 1);
        $select->group('t4.id');
        $select->order('t4.trades DESC');
        $select->limit(9223372036854775807);

        $dbAdapter = new Adapter($config["db_settings"]);

        $sql = new Sql($dbAdapter);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();

    }

    public function getUserInfo(User $user, $config)
    {
        $subSelect1 = new Select("user");
        $subSelect1->columns([
            'id' => 'id',
            'first_name' => 'first_name',
            'name' => 'name',
            'email' => 'email',
            'trades' => new Expression('SUM(IF(trade.status = 2, 1, 0))')
        ]);
        $subSelect1->join('listing', 'user.id = listing.user_id', [], Join::JOIN_LEFT);
        $subSelect1->join('trade', 'listing.id = trade.listing_id', [], Join::JOIN_LEFT);
        $subSelect1->group('id');

        $subSelect2 = new Select("user");
        $subSelect2->columns([
            'id' => 'id',
            'first_name' => 'first_name',
            'name' => 'name',
            'email' => 'email',
            'trades' => new Expression('SUM(IF(trade.status = 2, 1, 0))')
        ]);
        $subSelect2->join('trade_offer', 'user.id = trade_offer.user_id', [], Join::JOIN_LEFT);
        $subSelect2->join('trade', ' trade_offer.trade_id = trade.id', [], Join::JOIN_LEFT);
        $subSelect2->group('id');

        $subSelect1->combine($subSelect2);
        $select = new Select(['t1' => $subSelect1]);
        $select->columns(["id" => "id", "name" => new Expression("CONCAT(t1.first_name, ' ', t1.name)"), "trades" => new Expression("SUM(t1.trades)")]);

        $select->where->equalTo('id', $user->getId());
        $select->group('id');

        $dbAdapter = new Adapter($config["db_settings"]);
        $sql = new Sql($dbAdapter);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();

    }
}