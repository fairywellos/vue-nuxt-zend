<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class RatingRepository extends EntityRepository
{

    public function getUserRating( $userId, $config)
    {
        $subSelect = new Select("rating");
        $subSelect->columns(["rating"]);
        $subSelect->join("trade","trade.id = rating.trade_id",[]);
        $subSelect->join("trade_offer","trade_offer.trade_id = trade.id",[]);
        $subSelect->join("listing","listing.id = trade.listing_id",[]);
        $subSelect->where
            ->notEqualTo("rating.user_id",$userId)
            ->and
            ->nest()
                ->equalTo("listing.user_id",$userId)
                ->or
                ->equalTo("trade_offer.user_id",$userId)
            ->unnest();
        $subSelect->group("trade.id");

        $select = new Select(["t1" => $subSelect]);
        $select->columns(["rating" => new Expression("avg(t1.rating)")]);

        $dbAdapter = new Adapter($config["db_settings"]);
        $sql = new Sql($dbAdapter);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $results = $resultSet->toArray();

        return $results;
    }

}