<?php

namespace Application\Repository;

use Application\Entity\Listing;
use Application\Entity\Trade;
use Application\Entity\User;
use Application\Service\UploadManager;
use Doctrine\ORM\EntityRepository;

class ListingRepository extends EntityRepository
{
    const MAX_LIMIT = 40;
    const FIRST_PAGE = 1;
    const MIN_TEXT_LENGTH_Q = 2;

    /**
     * Filter params
     *
     * @var array
     */
    private $filterParams = [
        'limit', //limit mysql
        'page', //(page - 1) * limit = offset mysql
        'user', //user id
        'category', //int/string category id
        'fields', //displayed fields
        'q', //search query
        'order_by', //order mysql
        'price_min',
        'price_max',
        'trade_type',
        'id',
        'show_saved', //bool
        'status',
        'location',
    ];

    /**
     * Displayed fields
     *
     * @var array
     */
    private $availableFields = [
        'id' => 'l.id',
        'title' => 'l.title',
        'description' => 'l.description',
        'price' => 'l.price',
        'qty' => 'l.qty',
        'location' => "concat(loc.city, ', ' ,loc.state) as location",
        'status' => 'l.status',
        'created' => 'l.dateAdded',
        'expires' => 'l.availability',
        'user_name' => "CASE WHEN (u.privacy=1) THEN concat( u.firstName, ' ' , u.name) ELSE u.username END as user_name",
        'user_id' => 'u.id as user_id',
        'user_photo' => 'u.photoUrl as user_photo',
        'sample_account' => "CASE WHEN (u.userType=5) THEN 1 ELSE 0 END as sample_account",
        'category_id' => 'c.id as category_id',
        'main_photo' => 'p.name as photoName',
        'trade_offers_count' => 'count(t.id) as tradeOffers',
        'trade_type' => 'l.tradeOrCash as trade_type',
    ];

    private $fields = [];

    /**
     * @var User
     */
    private $currentUser;

    private $order = [
        'ASC',
        'DESC',
    ];

    /**
     * Total items from query
     *
     * @var int
     */
    private $totalItems = 0;

    /**
     * Max results
     *
     * @var int
     */
    private $maxResults = self::MAX_LIMIT;

    /**
     * Page
     *
     * @var int
     */
    private $page = self::FIRST_PAGE;

    /**
     * Filter by params
     *
     * @param $params array
     * @return array
     */
    public function filterBy($params)
    {
        $params = $this->paramsFilter($params);
        $query = $this->createQueryBuilder('l');

        /**
         * Set displayed fields
         */
        /*if($this->getCurrentUser()->getId()){
            $this->addAvailableField('saved', 'case when sb.id is not null then 1 else 0 end as saved');
        }*/

        if(isset($params['fields']) and $fields = $this->fieldsFilter($params['fields'])){
            $this->setFields($fields);
        }else{
            $this->setFields($this->getAvailableFields());
        }

        $query->select($this->getFields());

        if(key_exists('main_photo', $this->getFields())){
            $query->leftJoin('l.photos','p','WITH','p.main = 1');
        }

        if(key_exists('category_id', $this->getFields())){
            $query->leftJoin('l.mainCategory','c');
        }
        if (key_exists('location', $this->getFields())) {
            $query->leftJoin('l.location', 'loc');
        }

        if(key_exists('user_name', $this->getFields()) || key_exists('user_id', $this->getFields()) || key_exists('user_photo', $this->getFields())){
            $query->leftJoin('l.user','u');
        }

        //if(key_exists('saved', $this->getFields()) and $this->getCurrentUser()->getId()){
        if(!empty($params['show_saved']) and $this->getCurrentUser()->getId()){
            $query->leftJoin(
                'l.savedBy',
                'sb',
                'WITH',
                sprintf('sb.id = %d', $this->getCurrentUser()->getId())
            );
        }

        if(key_exists('trade_offers_count', $this->getFields())){
            $query->leftJoin('l.trades','t', "WITH", 't.status = :tradeStatus')
                ->setParameter('tradeStatus',Trade::PENDING);

        }

        if(!empty($params['q']) && strlen($params['q']) >= self::MIN_TEXT_LENGTH_Q){
            $query->leftJoin('l.tags','tags');
        }

        $query->groupBy('l.id');

        /**
         * Filters
         */
        if(!empty($params['id'])){
            $query
                ->andWhere('l.id = :id')
                ->setParameter('id', (int)$params['id']);
        }
        if(empty($params['status'])){
            $query->andWhere('l.status = :status')
                ->setParameter('status',Listing::ACTIVE);
            $currentDate = new \DateTime();
            $query->andWhere('l.availability >= :availability')
                ->setParameter("availability",$currentDate);

        }elseif($params["status"] !== "all"){
            $query->andWhere('l.status = :status')
                ->setParameter('status',$params["status"]);
        }
        if(!empty($params['user'])){
            $query
                ->andWhere('l.user = :userId')
                ->setParameter('userId', (int)$params['user']);
        }

        if(!empty($params['category'])){
            $getCategory = array_filter(array_map('trim', explode(',', $params['category'])));

            $query
                ->andWhere('l.mainCategory IN (:categoryIds)')
                ->setParameter('categoryIds', $getCategory);
        }

        if(!empty($params['trade_type'])){
            $getTradeType = array_filter(array_map('trim', explode(',', $params['trade_type'])));

            $getTradeType = (
                in_array(Listing::CASH_OFFER, $getTradeType) &&
                in_array(Listing::TRADE_OFFER, $getTradeType)
            ) ? Listing::TRADE_AND_CASH_OFFER : (int) $getTradeType[0];

            $query
                ->andWhere('l.tradeOrCash = :tradeType')
                ->setParameter('tradeType', $getTradeType);
        }

        if(!empty($params['price_min'])){
            $query
                ->andWhere('l.price >= :priceMin')
                ->setParameter('priceMin', (float)$params['price_min']);
        }

        if(!empty($params['price_max'])){
            $query
                ->andWhere('l.price <= :priceMax')
                ->setParameter('priceMax', (float)$params['price_max']);
        }

        if(!empty($params['q']) && strlen($params['q']) >= self::MIN_TEXT_LENGTH_Q){
            $query
                ->andWhere('l.title LIKE :searchValue or l.description LIKE :searchValue or l.metaTags LIKE :searchValue')
                ->setParameter('searchValue', '%'.$params['q'].'%');
        }

        if(!empty($params['show_saved'])){
            $query
                ->andWhere('sb.id = :savedBy')
                ->setParameter('savedBy', $this->getCurrentUser()->getId());
        }

        if(!empty($params['location'])){
            $query
                ->andWhere('l.location = :location')
                ->setParameter('location', (int)$params['location']);
        }

        if(isset($params['order_by'])){
            if(is_array($params['order_by'])){
                foreach ($params['order_by'] as $orderBy){
                    $orderByArr = $this->orderBy($orderBy);

                    $query
                        ->addOrderBy($orderByArr['sort'], $orderByArr['order']);
                }
            }else{
                $orderByArr = $this->orderBy($params['order_by']);

                $query
                    ->addOrderBy($orderByArr['sort'], $orderByArr['order']);
            }
        }else{
            $query->orderBy("l.dateAdded","desc");
        }

        /**
         * Get total items
         */
        $queryCounter = clone $query;
        $queryCounter
            ->select('COUNT(l.id) as totalItems')
            ->setMaxResults(1);

        $totalItems = $queryCounter
            ->getQuery()
            ->execute();
        // print_r($totalItems);
        $this->setTotalItems(count($totalItems) ?? 0);

        /**
         * set max result and first result
         */
        if(isset($params['limit'])){
            $this->setMaxResults(abs($params['limit']));
        }

        if(isset($params['page'])){
            $this->setPage(abs($params['page']));
        }

        $query->setMaxResults($this->getMaxResults());
        $query->setFirstResult(($this->getPage() - 1) * $this->getMaxResults());
        $result = $query->getQuery()->execute();

        if(key_exists('main_photo', $this->getFields())){
            $uploadManager = new UploadManager();
            foreach ($result as $key => $item){
                if(isset($item["photoName"])){
                    $result[$key]["mainPhotoUrl"] =  $uploadManager->getFileUrl("listing", $item["photoName"]);
                }
            }
        }
        return $result;
    }

    /**
     * Params from query link
     *
     * @param $params array
     * @return array
     */
    private function paramsFilter($params){
        if(empty($params)){
            return [];
        }

        $removeParams = array_keys(array_diff_key($params, array_flip($this->filterParams)));

        foreach ($removeParams as $removeParam){
            unset($params[$removeParam]);
        }

        return $params;
    }

    /**
     * Fields from query link
     *
     * @param $params array
     * @return array
     */
    private function fieldsFilter($fields){
        if(empty($fields) or !is_string($fields)){
            return [];
        }

        $fields = array_filter(array_map('trim', explode(',', $fields)));

        $availableFields = array_intersect_key($this->getAvailableFields(), array_flip($fields));

        return $availableFields;
    }

    /**
     * @return array
     */
    private function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return ListingRepository
     */
    private function setFields(array $fields): ListingRepository
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param $key
     * @param $select
     * @return $this
     */
    private function addField($key, $select)
    {
        $this->fields[$key] = $select;
        return $this;
    }

    /**
     * @param string $orderBy
     * @return array
     */
    private function orderBy($orderBy){
        $orderBy = array_filter(array_map('trim', explode(',', $orderBy)));

        return [
            'sort' => isset($orderBy[0]) && array_key_exists($orderBy[0], $this->getAvailableFields()) ? $orderBy[0] : '',
            'order' => isset($orderBy[1]) && in_array($orderBy[1], $this->order) ? $orderBy[1] : '',
        ];
    }

    /**
     * Set total items from query
     *
     * @param int $total
     */
    private function setTotalItems($total){
        $this->totalItems = (int)$total;
    }

    /**
     * Get total items from query
     *
     * @return int
     */
    public function getTotalItems(){
        return $this->totalItems;
    }

    /**
     * Set max results
     *
     * @param int $maxResults
     */
    private function setMaxResults($maxResults){
        $this->maxResults = (int)$maxResults;
    }

    /**
     * Get max results
     *
     * @return int
     */
    public function getMaxResults(){
        return $this->maxResults;
    }

    /**
     * Set page
     *
     * @param int $page
     */
    private function setPage($page){
        $this->page = (int)$page;
    }

    /**
     * Get page
     *
     * @return int
     */
    public function getPage(){
        return $this->page;
    }

    /**
     * @return User
     */
    public function getCurrentUser() : User
    {
        return $this->currentUser;
    }

    /**
     * @param User $currentUser
     * @return ListingRepository
     */
    public function setCurrentUser(User $currentUser): ListingRepository
    {
        $this->currentUser = $currentUser;
        return $this;
    }

    /**
     * @return array
     */
    public function getAvailableFields(): array
    {
        return $this->availableFields;
    }

    /**
     * @param array $availableFields
     * @return ListingRepository
     */
    public function setAvailableFields(array $availableFields): ListingRepository
    {
        $this->availableFields = $availableFields;
        return $this;
    }

    /**
     * @param $key
     * @param $select
     * @return $this
     */
    private function addAvailableField($key, $select)
    {
        $this->availableFields[$key] = $select;
        return $this;
    }
}