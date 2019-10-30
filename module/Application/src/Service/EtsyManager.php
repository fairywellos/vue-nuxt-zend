<?php

namespace Application\Service;

use Application\Entity\EtsyTrackingCategories;
use Application\Entity\EtsyTrackingListings;
use Application\Entity\EtsyTrackingUsers;
use Application\Entity\Listing;
use Application\Entity\Location;
use Application\Entity\MainCategory;
use Application\Entity\TrackingRequests;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;

class EtsyManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    private $serviceManager;

    /**
     * @param array $options
     * @return bool|string
     */
    public function sendRequest($options)
    {
        $keyString = $this->serviceManager->get("config")["etsy_options"]["key_string"];
        $temp_params = $options["params"];
        $params = "";

        foreach ($temp_params as $key => $value) {
            $params .= "&$key=$value";
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://openapi.etsy.com/v2/$options[route]?api_key=" . $keyString . $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }
    }

    public function exchangeListingData($etsy_listing)
    {
        $trackingRequestRepo = $this->entityManager->getRepository(TrackingRequests::class);

        $data = [
            "listingType" => 1,
            "title" => $etsy_listing['title'],
            "description" => $etsy_listing['description'],
            "price" => $etsy_listing['price'],
            "qty" => $etsy_listing['quantity'],
            "condition" => 2,
            "tradeOrCash" => 2,
            "shipping" => 0,
            "material" => explode(', ', $etsy_listing['materials']),
            "localTradesOnly" => 0,
            "year" => date("Y", $etsy_listing['creation_tsz']),
            "available_date" => "Now",
            "availability" => new \DateTime(date('Y-m-d H:i:s', strtotime('+3 month'))),
            "meta_tags" => explode(', ', $etsy_listing['tags']),
            "listing_tags" => $etsy_listing['tags'],
        ];

        //brand
        $brand_response = $this->sendRequest([
            "route" => "shops/listing/" . $etsy_listing["listing_id"],
        ]);
        $data['brand'] = $brand_response['results'][0]['shop_name'];

        //location
        $user_response = $this->sendRequest([
            "route" => "users/$etsy_listing[user_id]/profile",
        ]);

        $locationRepo = $this->entityManager->getRepository(Location::class);

        $location = $locationRepo->findLocationByCityAndState($user_response['results'][0]['city'], false, 1);

        $data['location'] = $locationRepo->findOneBy(["id" => $location[0]["id"]]);

        //main category
        $etsyCategoryRepo = $this->entityManager->getRepository(EtsyTrackingCategories::class);
        $category_id = $etsyCategoryRepo->findOneBy(["etsyId" => $etsy_listing["category_path_ids"][0]]);

        $data['main_category_id'] = $category_id->getMainCategory()->getId();

        //photos
        $image_response = $this->sendRequest([
            "route" => "/listings/$etsy_listing[listing_id]/images"
        ]);

        $images = array_column($image_response['results'], "url_570xN");
        $mainFolder = "listing";

        $uploadManager = $this->getServiceManager()->get(UploadManager::class);
        $data['photos'] = [];

        if ($images) {
            foreach ($images as $image) {
                $temp = tempnam(sys_get_temp_dir(), 'TMP_');

                preg_match('/^.*\/(.*\.[jpg|png]+).*$/', $image, $img, PREG_OFFSET_CAPTURE,0);
                $img = $img[1][0];

                preg_match('/^.*\.([jpg|png]+).*$/', $image, $extension, PREG_OFFSET_CAPTURE,0);

                $temp_img = file_get_contents($image, false);

                file_put_contents($temp, $temp_img);

                $tempFile = [
                    "name" => $img,
                    "type" => "image/" . $extension[1][0],
                    "tmp_name" => $temp,
                    "error" => 0,
                    "size" => filesize($temp)
                ];

                $data['photos'][]  = $uploadManager->uploadFile($mainFolder, $tempFile);
            }
        }

        if (!empty($data["photos"])) {
            $data["photos"][0]["main"] = 1;
        }

        $trackingRequestRepo->addRequest('etsy', 3);

        return $data;
    }

    public function exchangeUserData($etsy_user_id)
    {
        $trackingRequestRepo = $this->entityManager->getRepository(TrackingRequests::class);

        $user_response = $this->sendRequest([
            "route" => "users/$etsy_user_id/profile",
        ]);

        $trackingRequestRepo->addRequest('etsy', 1);

        $user_response = $user_response['results'][0];

        $data = [
            "first_name" => $user_response['first_name'],
            "name" => $user_response['last_name'],
            "email" => $user_response['login_name'] . "@tradelist.net",
            "description" => $user_response['bio'],
            "username" => $user_response['login_name']
        ];

        //location
        $locationRepo = $this->entityManager->getRepository(Location::class);

        $location = $locationRepo->findLocationByCityAndState($user_response['city'], false, 1);

        $data['location'] = $locationRepo->findOneBy(["id" => $location[0]["id"]]);

        //photo
        $image = $user_response['image_url_75x75'];
        $mainFolder = "user";

        $uploadManager = $this->getServiceManager()->get(UploadManager::class);

        $temp = tempnam(sys_get_temp_dir(), 'TMP_');

        preg_match('/^.*\/(.*\.[jpg|png]+).*$/', $image, $img, PREG_OFFSET_CAPTURE,0);
        $img = $img[1][0];

        preg_match('/^.*\.([jpg|png]+).*$/', $image, $extension, PREG_OFFSET_CAPTURE,0);

        $temp_img = file_get_contents($image, false);

        file_put_contents($temp, $temp_img);

        $tempFile = [
            "name" => $img,
            "type" => "image/" . $extension[1][0],
            "tmp_name" => $temp,
            "error" => 0,
            "size" => filesize($temp)
        ];

        $data['photo']  = $uploadManager->uploadFile($mainFolder, $tempFile);

        return $data;
    }

    public function addEtsyListing($etsy_listing)
    {
        $listingManager = $this->getServiceManager()->get(ListingManager::class);
        $userManager = $this->getServiceManager()->get(UserManager::class);
        $uploadManager = $this->getServiceManager()->get(UploadManager::class);

        $etsyUser = $this->entityManager->getRepository(EtsyTrackingUsers::class)->findOneBy(["etsyId" => $etsy_listing["user_id"]]);
        $etsyListing = $this->entityManager->getRepository(EtsyTrackingListings::class)->findOneBy(["etsyId" => $etsy_listing["listing_id"]]);

        if(!$etsyUser) {
            $etsy_data = $this->exchangeUserData($etsy_listing["user_id"]);
            $user = $userManager->addNewExternUser($etsy_data);

            $entity = new EtsyTrackingUsers();
            $entity->setEtsyId($etsy_listing["user_id"]);
            $entity->setUser($user);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } else {
            $user = $etsyUser->getUser();
        }

        if(!$etsyListing) {
            //create Listing
            $etsy_data = $this->exchangeListingData($etsy_listing);
            $listing_id = $listingManager->addListing($etsy_data, $user);

            //add into tracking tables
            $listing = $this->entityManager->getRepository(Listing::class)->findOneBy(["id" => $listing_id]);

            $entity = new EtsyTrackingListings();
            $entity->setEtsyId($etsy_listing["listing_id"]);
            $entity->setListing($listing);
            $entity->setUrl($etsy_listing["url"]);

            $brand_response = $this->sendRequest([
                "route" => "shops/listing/" . $etsy_listing["listing_id"],
            ]);
            $entity->setShopId($brand_response['results'][0]['shop_id']);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } else {
            $etsy_data = $this->exchangeListingData($etsy_listing);
            $tradelist_listing = $etsyListing->getListing();

            foreach ($tradelist_listing->getPhotos() as $photo) {
                $uploadManager->deleteFile("listing", $photo->getName());

                $tradelist_listing->removePhoto($photo);
                $this->entityManager->remove($photo);
            }

            $this->entityManager->persist($tradelist_listing);
            $this->entityManager->flush();

            $listingManager->addListing($etsy_data, $user, $tradelist_listing);
        }
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

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param mixed $serviceManager
     * @return $this
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}