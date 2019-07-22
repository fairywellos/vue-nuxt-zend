<?php

namespace Application\Service;


use Application\Entity\Rating;
use Application\Entity\Trade;
use Application\Entity\TradeOffer;
use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Filter\StripNewlines;

class UserManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $config;

    public function __construct($entityManager,$config)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    /**
     * @param $data
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addNewUser($data)
    {
        $user = new User();
        $user->setFirstName($data["first_name"]);
        $user->setName($data["name"]);
        $user->setUsername($data["username"]);
        $user->setEmail($data["email"]);
        $user->setPassword($data["password"]);
        $user->setPublic(User::PUBLIC);
        $user->setPrivacy(User::PRIVATE);
        $user->setStatus(User::NOT_ACTIVATED);
        $user->setUserType(User::BASIC);
        $currentDate =  new \DateTime(date('Y-m-d H:i:s'));
        $membershipExpires = new \DateTime(date('Y-m-d H:i:s', strtotime('+1 month')));
        $activationExpires = new \DateTime(date('Y-m-d H:i:s', strtotime('+24 hour')));
        $activationCode = $this->random_str(6);
        $user->setActivationCode($activationCode);
        $user->setActivationExpires($activationExpires);
        $user->setDateAdded($currentDate);
        $user->setDateModified($currentDate);
        $user->setMembershipExpires($membershipExpires);
        $user->setCredits(3);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param User $user
     * @param $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUser(User $user, $data)
    {
        $user->setLocation($data["location"] ?? $user->getLocation());
        $user->setDescription($data["description"] ?? $user->getDescription());
        $user->setFirstName($data["firstName"] ?? $user->getFirstName());
        $user->setName($data["lastName"] ?? $user->getName());
        $user->setUsername($data["userName"] ?? $user->getUsername());
        $user->setPhoto($data["photo"] ?? $user->getPhoto());
        $user->setPhotoUrl($data["photoUrl"] ?? $user->getPhotoUrl());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


    public function regenerateUserActivationCode(User $user)
    {
        $activationCode = $this->random_str(6);
        $user->setActivationCode($activationCode);
        $activationExpires = new \DateTime(date('Y-m-d H:i:s', strtotime('+24 hour')));
        $user->setActivationExpires($activationExpires);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param Trade $trade
     * @param User $currentUser
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUsersRating(Trade $trade,User $currentUser)
    {
        if($trade->getListing()->getUser()->getId() != $currentUser->getId()){
            $user = $trade->getListing()->getUser();
            $rating = $this->entityManager->getRepository(Rating::class)->getUserRating($trade->getListing()->getUser()->getId(),$this->config);
            $user->setRating($rating[0]["rating"]);
            $this->entityManager->persist($user);
        }
        $tradeOffers = $trade->getTradeOffers();
        /**
         * @var TradeOffer $tradeOffer
         */
        foreach ($tradeOffers as $tradeOffer){
            $user = $tradeOffer->getUser();
            if($currentUser->getId() != $user->getId()){
                $rating = $this->entityManager->getRepository(Rating::class)->getUserRating($user->getId(),$this->config);
                $user->setRating($rating[0]["rating"]);
                $this->entityManager->persist($user);

            }
        }

        $this->entityManager->flush();
    }


    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters to select from
     * @return string
     */
    private function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

}