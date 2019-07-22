<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class LocationRepository extends EntityRepository
{
    public function getLocationByCoordinates($lat, $long, $config)
    {
        $subSelect = new Select("zip_code");
        $subSelect->columns(['location_id' => 'location_id']);
        $subSelect->order(new Expression("abs(zip_code.lat - (?)) + abs(zip_code.long - (?))", [$lat, $long]));
        $subSelect->limit(1);

        $select = new Select("location");
        $select->columns(['city' => 'city', 'state' => 'state', "id" => "id"]);
        $select->join(["order_zip_codes" => $subSelect],"location.id = order_zip_codes.location_id", array());

        $dbAdapter = new Adapter($config["db_settings"]);
        $sql = new Sql($dbAdapter);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();
    }

    public function findLocationByZipCode($zipCode,$maxResults = 5)
    {
        $query = $this->createQueryBuilder('l');
        $query->select("l.id,l.city as name, l.state as abbreviation ");
        $query->join("l.zipCodes", "zc");
        $query->where("zc.zipCode like :zipCode")->setParameter("zipCode","%" . $zipCode . "%");
        $query->setMaxResults($maxResults);
        $query->groupBy("l.id");
        $result = $query ->getQuery()->execute();

        return $result;
    }

    public function findLocationByCityAndState($city,$state = false, $maxResults = 5)
    {
        $query = $this->createQueryBuilder('l');
        $query->select("l.id,l.city as name, l.state as abbreviation ");
        $query->where("l.city like :city")->setParameter("city","%" . $city . "%");
        if($state){
            $query->andWhere("l.state like :state")->setParameter("state","%" . $state . "%");
        }
        $query->groupBy("l.id");
        $query->setMaxResults($maxResults);

        $result = $query ->getQuery()->execute();

        return $result;

    }

    public function getAllLocations($maxResults = 5)
    {
        $query = $this->createQueryBuilder('l');
        $query->select("l.id,l.city as name, l.state as abbreviation ");
        $query->join("l.zipCodes", "zc");
        $query->groupBy("l.id");
        $query->setMaxResults($maxResults);

        $result = $query ->getQuery()->execute();

        return $result;
    }
}