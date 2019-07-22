<?php

namespace Application\Repository;

use Application\Entity\Listing;
use Application\Entity\Trade;
use Application\Entity\User;
use Application\Service\UploadManager;
use Doctrine\ORM\EntityRepository;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Join;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class TradeRepository extends EntityRepository
{
    public function checkForDuplicateCase1($data,User $user)
    {

        $query = $this->createQueryBuilder('t');
        $query->select("t.id");
        $query->leftJoin("t.tradeOffers","to");
        $query->where("to.user = :user")
            ->setParameter("user",$user);
        $query->andWhere("t.tradeType = :tradeType")
            ->setParameter("tradeType", $data["tradeType"]);
        $query->andWhere("to.cashValue = :cashValue")
            ->setParameter("cashValue",$data["tradeOffers"][0]["cashValue"]);
        $query->andWhere("t.listing = :listing")
            ->setParameter("listing",$data["listing"]);
        $query->setMaxResults(1);
        $result = $query ->getQuery()->execute();

        return $result;
    }

    public function getUserTradeOffers(User $user,$config)
    {
        $subSelect = new Select("trade");
        $subSelect->columns(["id" => "id", "tradeOfferValue" => new Expression("SUM(IFNULL(listing.price,0)+trade_offer.cash_value)")]);
        $subSelect->join("trade_offer","trade.id = trade_offer.trade_id",[]);
        $subSelect->join("listing","trade_offer.listing_id =listing.id",[],Join::JOIN_LEFT);
        $subSelect->where->equalTo("trade_offer.user_id",$user->getId());
        $subSelect->group("trade.id");

        $subSelect2 =  new Select("trade_offer");
        $subSelect2->columns(["trade_id" => "trade_id","mprice" => new Expression("max(listing.price)")]);
        $subSelect2->join("listing","listing.id = trade_offer.listing_id",[],Join::JOIN_LEFT);
        $subSelect2->group("trade_offer.trade_id");

        $select = new Select("trade");
        $select->columns(["id","status"]);
        $select->join("trade_offer","trade.id = trade_offer.trade_id",[]);
        $select->join(["l1" => "listing"],"l1.id = trade.listing_id",["listingTitle" => "title", "listingID" => "id"]);
        $select->join(["p1" => "photo"],new Expression('(l1.id = p1.listing_id and p1.main = 1)'),["listingPhotoName" => "photo_name"],Join::JOIN_LEFT);
        $select->join(["l2" => "listing"],"l2.id = trade_offer.listing_id",["itemTitle" => "title"],Join::JOIN_LEFT);
        $select->join(["c1" => "main_category"],"c1.id = l2.main_category_id",["categorySlug" => "slug", "categoryName" =>  "name", "categoryColor" => "color_code"],Join::JOIN_LEFT);
        $select->join(["p2" => "photo"],new Expression('(p2.listing_id = l2.id and p2.main = 1)'),["itemPhoto" => "photo_name"],Join::JOIN_LEFT);
        $select->join(["sumtrade" => $subSelect],"sumtrade.id = trade.id",["tradeOfferValue"]);
        $select->join(["ttt" => $subSelect2],new Expression("ttt.trade_id = trade_offer.trade_id and (l2.price = ttt.mprice or l2.id is null)"),[]);
        $select->where->equalTo("trade_offer.user_id",$user->getId());

        $dbAdapter = new Adapter($config["db_settings"]);
        $sql = new Sql($dbAdapter);


        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $results = $resultSet->toArray();
        $uploadManager = new UploadManager();
        foreach ($results as $key => $item){
            if(isset($item["itemPhoto"])){
                $results[$key]["itemPhotoUrl"] =  $uploadManager->getFileUrl("listing", $item["itemPhoto"]);
            }
            if(isset($item["listingPhotoName"])){
                $results[$key]["listingPhotoUrl"] =  $uploadManager->getFileUrl("listing", $item["listingPhotoName"]);
            }
        }

        return $results;


    }

    public function getUserAcceptedTrades(User $user,$config)
    {
        $subSelect = new Select("trade");
        $subSelect->columns(["id" => "id", "tradeOfferValue" => new Expression("SUM(IFNULL(listing.price,0)+trade_offer.cash_value)")]);
        $subSelect->join("trade_offer","trade.id = trade_offer.trade_id",[]);
        $subSelect->join("listing","trade_offer.listing_id =listing.id",[],Join::JOIN_LEFT);
        $subSelect->group("trade.id");

        $subSelect2 =  new Select("trade_offer");
        $subSelect2->columns(["trade_id" => "trade_id","mprice" => new Expression("max(listing.price)")]);
        $subSelect2->join("listing","listing.id = trade_offer.listing_id",[],Join::JOIN_LEFT);
        $subSelect2->group("trade_offer.trade_id");

        $select = new Select("trade");
        $select->columns(["id"]);
        $select->join("trade_offer","trade.id = trade_offer.trade_id",["tradeUser" => "user_id"]);
        $select->join(["l1" => "listing"],"l1.id = trade.listing_id",["listingTitle" => "title", "listingID" => "id", "listingUser" => "user_id"]);
        $select->join(["p1" => "photo"],new Expression('(l1.id = p1.listing_id and p1.main = 1)'),["listingPhotoName" => "photo_name"],Join::JOIN_LEFT);
        $select->join(["l2" => "listing"],"l2.id = trade_offer.listing_id",["itemTitle" => "title"],Join::JOIN_LEFT);
        $select->join(["c1" => "main_category"],"c1.id = l2.main_category_id",["itemCategorySlug" => "slug", "itemCategoryName" =>  "name","itemCategoryColor" => "color_code"],Join::JOIN_LEFT);
        $select->join(["c2" => "main_category"],"c2.id = l1.main_category_id",["listingCategorySlug" => "slug", "listingCategoryName" =>  "name","listingCategoryColor" => "color_code"],Join::JOIN_LEFT);
        $select->join(["p2" => "photo"],new Expression('(p2.listing_id = l2.id and p2.main = 1)'),["itemPhoto" => "photo_name"],Join::JOIN_LEFT);
        $select->join(["r" => "rating"],new Expression('(r.trade_id = trade.id and r.user_id = ' . $user->getId() . ')'),["rating" => "rating", "ratingReview" => "review"],Join::JOIN_LEFT);
        $select->join(["sumtrade" => $subSelect],"sumtrade.id = trade.id",["tradeOfferValue"]);
        $select->join(["ttt" => $subSelect2],new Expression("ttt.trade_id = trade_offer.trade_id and (l2.price = ttt.mprice or l2.id is null)"),[]);
        $select->where->nest()
            ->equalTo("trade_offer.user_id",$user->getId())
            ->or
            ->equalTo("l1.user_id",$user->getId())
            ->unnest()
            ->and->equalTo("trade.status",Trade::ACCEPTED);

        $dbAdapter = new Adapter($config["db_settings"]);
        $sql = new Sql($dbAdapter);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $results = $resultSet->toArray();
        $uploadManager = new UploadManager();
        foreach ($results as $key => $item){
            if(isset($item["itemPhoto"])){
                $results[$key]["itemPhotoUrl"] =  $uploadManager->getFileUrl("listing", $item["itemPhoto"]);
            }
            if(isset($item["listingPhotoName"])){
                $results[$key]["listingPhotoUrl"] =  $uploadManager->getFileUrl("listing", $item["listingPhotoName"]);
            }
        }

        return $results;


    }


}