<?php

namespace Application\Controller;


use Application\Entity\EtsyTrackingUsers;
use Application\Entity\Listing;
use Application\Entity\Location;
use Application\Entity\Trade;
use Application\Entity\User;
use Application\Repository\ListingRepository;
use Application\Repository\UserRepository;
use Application\Service\RatingManager;
use Application\Service\UploadManager;
use Application\Service\UserManager;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\Validator\File\IsImage;

/**
 * @method User currentUser()
 * Class UserController
 * @package Application\Controller
 */
class UserController extends ApiController
{

    /**
     * Entity manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var RatingManager
     */
    private $ratingManager;

    /**
     * @var UploadManager
     */
    private $uploadManager;

    /**
     * @var array
     */
    private $config;

    public function __construct($entityManager, $userManager, $ratingManager, $uploadManager, $config)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->ratingManager = $ratingManager;
        $this->uploadManager = $uploadManager;
        $this->config = $config;
    }

    public function getUserAction()
    {
        /**
         * @var User $user ;
         */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(["id" => $this->tokenPayload->id]);

        $this->httpStatusCode = 200;
        $this->apiResponse = $user->getAuthInfo();
        return $this->createResponse();

    }

    public function findTradePartnersAction()
    {
        $params = $this->params()->fromPost();

        /**
         * @var UserRepository $notificationRepo
         */
        $userRepo = $this->entityManager->getRepository(User::class);
        $user_id = $this->currentUser()->getId();

        $partners = $userRepo->getUsersByFilters($params, $user_id, $this->config);

        $this->httpStatusCode = 200;
        $this->apiResponse = $partners;
        return $this->createResponse();
    }

    public function getTradePartnersAction()
    {
        /**
         * @var UserRepository $notificationRepo
         */
        $userRepo = $this->entityManager->getRepository(User::class);
        $user_id = $this->currentUser()->getId();

        $partners = $userRepo->getTradePartners($user_id, $this->config);

        $this->httpStatusCode = 200;
        $this->apiResponse = $partners;
        return $this->createResponse();
    }

    public function followingAction() {
        $params = $this->params()->fromPost();

        //check if all parameters are given
        if(empty($params['user_id'])){
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters given";
            return $this->createResponse();
        }

        /**
         * @var UserRepository $notificationRepo
         */
        $userRepo = $this->entityManager->getRepository(User::class);
        $following = $userRepo->findOneById($params['user_id']);
        $follower = $this->currentUser();

        if ($follower->getId() == $params['user_id']) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "You cannot follow yourself";
            return $this->createResponse();
        }

        $follower->follow($following);

        $this->entityManager->persist($follower);
        $this->entityManager->flush();

        $this->httpStatusCode = 200;
        $this->apiResponse['message'] = 'success';
        return $this->createResponse();
    }

    public function unfollowAction() {
        $params = $this->params()->fromPost();

        //check if all parameters are given
        if(empty($params['user_id'])){
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters given";
            return $this->createResponse();
        }

        /**
         * @var UserRepository $notificationRepo
         */
        $userRepo = $this->entityManager->getRepository(User::class);
        $following = $userRepo->findOneById($params['user_id']);
        $follower = $this->currentUser();

        if ($follower->getId() == $params['user_id']) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "You cannot unfollow you";
            return $this->createResponse();
        }

        $follower->unfollow($following);

        $this->entityManager->persist($follower);
        $this->entityManager->flush();

        $this->httpStatusCode = 200;
        $this->apiResponse['message'] = 'success';
        return $this->createResponse();
    }

    public function indexAction()
    {
        $data = $this->params()->fromQuery();

        if (empty($data["id"])) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No user id provided";
            return $this->createResponse();
        }

        /**
         * @var User $user ;
         */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(["id" => $data["id"]]);

        if ($user == null) {
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No user found for id: " . $data["id"];
            return $this->createResponse();
        }


        $this->httpStatusCode = 200;
        $this->apiResponse["data"] = $user->getPublicInfo();
        return $this->createResponse();
    }

    public function getUserProfileAction()
    {

        $data = $this->params()->fromQuery();

        $routeMatch = $this->getEvent()->getRouteMatch();
        $routeName = $routeMatch->getMatchedRouteName();

        $id = $data["id"] ?? false;
        if ($routeName == "user/my-profile/get_route") {
            $id = $this->tokenPayload->id;
        }

        if (!$id) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No user id provided";
            return $this->createResponse();
        }
        /**
         * @var UserRepository $userRepo
         */
        $userRepo =  $this->entityManager->getRepository(User::class);

        /**
         * @var User $user ;
         */
        $user = $userRepo->findOneBy(["id" => $id]);

        if ($user == null) {
            $this->httpStatusCode = 404;
            $this->apiResponse["message"] = "No user found for id: " . $id;
            return $this->createResponse();
        }

        $userResponse = $user->getProfileData(true);
        $listingStatus = "all";
        if ($routeName == "user/profile/get_route") {
            $listingStatus = null;
            $userResponse = $user->getProfileData();
            $userInfo = $userRepo->getUserInfo($user,$this->config);
            $userResponse["trades"] = $userInfo[0]["trades"];
        }
        /**
         * @var $listingRepository ListingRepository
         */
        $listingRepository = $this->entityManager->getRepository(Listing::class);

        $listingRepository->setCurrentUser($this->currentUser());

        $userListings = $listingRepository->filterBy([
                "user" => $id,
                "status" => $listingStatus,
                "fields" => "id,title,price,status,created,expires,main_photo"
            ]
        );

        $etsyUserRepo = $this->entityManager->getRepository(EtsyTrackingUsers::class);
        $userResponse['isEtsyUser'] = false;

        if($etsyUserRepo->findOneBy(["user" => $user])) {
            $userResponse['isEtsyUser'] = true;
        }

        $this->httpStatusCode = 200;
        $this->apiResponse["user"] = $userResponse;
        $this->apiResponse["userListings"] = $userListings;
        return $this->createResponse();
    }

    public function addReviewAction()
    {
        $data = $this->params()->fromPost();
        if (empty($data["tradeId"]) || !isset($data["rating"]) || !isset($data["review"])) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters sent";
            return $this->createResponse();
        }
        /**
         * @var User $user
         */
        $user = $this->currentUser();
        $data["user"] = $user;
        /**
         * @var Trade $trade
         */
        $trade = $this->entityManager->getRepository(Trade::class)->findOneBy(["id" => $data["tradeId"]]);
        if ($trade == null) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No trade found for id: " . $data["id"];
            return $this->createResponse();
        } elseif ($trade->getListing()->getUser()->getId() != $user->getId() &&
            $trade->getTradeOffers()[0]->getUser()->getId() != $user->getId()) {

            $this->httpStatusCode = 403;
            $this->apiResponse["message"] = "You are not part of this trade";
            return $this->createResponse();
        }
        $data["trade"] = $trade;

        $ratingData = $this->ratingManager->prepareData($data);


        try {
            $this->ratingManager->addRating($ratingData);
            $this->userManager->updateUsersRating($trade,$user);
            $this->httpStatusCode = 200;
            $this->apiResponse["message"] = "Trade rated";
        } catch (\Exception $exception) {
            $this->httpStatusCode = 500;
            $this->apiResponse["message"] = "Something went wrong";
            if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                $this->apiResponse["error_message"] = $exception->getMessage();
            } else {
                error_log("Caught $exception");
            }
            return $this->createResponse();
        }

        return $this->createResponse();
    }

    public function updateUserAction()
    {
        $data = $this->params()->fromPost();
        $files = $this->params()->fromFiles();


        if (empty($data["id"])) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters sent";
            return $this->createResponse();
        }
        /**
         * @var User $user
         */
        $user = $this->currentUser();
        if ($user->getId() != $data["id"]) {
            $this->httpStatusCode = 403;
            $this->apiResponse["message"] = "You can only update your profile";
            return $this->createResponse();
        }
        if (!empty($files) && isset($files["photoFile"])) {
            $user_pohto = $user->getPhoto();
            if (isset($user_pohto)) {
                try {
                    $this->uploadManager->deleteFile("user", $user_pohto);
                } catch (\Exception $exception) {
                    error_log("Caught $exception");
                }
            }
            if ($files["photoFile"]["error"] != 0) {
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "The was an error uploading your photo. Please try again!";
                return $this->createResponse();
            }

            $isImageValidator = new IsImage();
            if (!$isImageValidator->isValid($files["photoFile"])) {
                $this->httpStatusCode = 404;
                $this->apiResponse["message"] = "You can only upload images is your profile picture";
                return $this->createResponse();
            }


            try {
                $resultFiles = $this->uploadManager->uploadFile("user", $files["photoFile"]);
                $data["photo"] = $resultFiles["name"];
                $data["photoUrl"] = $resultFiles["url"];
            } catch (\Exception $exception) {

                $this->httpStatusCode = 500;
                $this->apiResponse["message"] = "Something went wrong";
                if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                    $this->apiResponse["error_message"] = $exception->getMessage();
                } else {
                    error_log("Caught $exception");
                }
                return $this->createResponse();

            }
        }
        if (!empty($data["location"])) {
            $location = $this->entityManager->getRepository(Location::class)->findOneBy(["id" => $data["location"]]);
            if ($location == null) {
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = "Invalid location selected";
                return $this->createResponse();
            }

            $data["location"] = $location;
        }else{
            $data["location"] = null;
        }
        try {
            $this->userManager->updateUser($user, $data);
            $this->httpStatusCode = 200;
            $this->apiResponse["message"] = "Profile updated";
        } catch (\Exception $exception) {
            $this->httpStatusCode = 500;
            $this->apiResponse["message"] = "Something went wrong";
            if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                $this->apiResponse["error_message"] = $exception->getMessage();
            }
            return $this->createResponse();
        }


        return $this->createResponse();

    }

    public function updatePrivacyAction()
    {
        $status = $this->params()->fromPost("status", false);
        if (!$status) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters sent";
            return $this->createResponse();
        }
        /**
         * @var User $user
         */
        $user = $this->currentUser();

        $privacy = User::PUBLIC;
        if ($status == "username") {
            $privacy = User::PRIVATE;
        }

        try {
            $user->setPrivacy($privacy);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->httpStatusCode = 200;
            $this->apiResponse["message"] = "Profile updated";
        } catch (\Exception $exception) {
            $this->httpStatusCode = 500;
            $this->apiResponse["message"] = "Something went wrong";
            if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                $this->apiResponse["error_message"] = $exception->getMessage();
            }
            return $this->createResponse();
        }

        return $this->createResponse();
    }

    public function updatePublicAction()
    {
        $status = $this->params()->fromPost("status", false);
        if (!$status) {
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Invalid parameters sent";
            return $this->createResponse();
        }
        /**
         * @var User $user
         */
        $user = $this->currentUser();

        $public= User::PUBLIC;
        if ($status == "Private") {
            $public = User::PRIVATE;
        }

        try {
            $user->setPublic($public);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->httpStatusCode = 200;
            $this->apiResponse["message"] = "Profile updated";
        } catch (\Exception $exception) {
            $this->httpStatusCode = 500;
            $this->apiResponse["message"] = "Something went wrong";
            if ($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()) {
                $this->apiResponse["error_message"] = $exception->getMessage();
            }
            return $this->createResponse();
        }

        return $this->createResponse();
    }


}