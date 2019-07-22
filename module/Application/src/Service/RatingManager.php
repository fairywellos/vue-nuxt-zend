<?php

namespace Application\Service;


use Application\Entity\Rating;
use Doctrine\ORM\EntityManager;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;

class RatingManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addRating($data)
    {
        $dateAdded  = new \DateTime(date('Y-m-d H:i:s'));

        $entity = $this->entityManager->getRepository(Rating::class)->findOneBy(["trade" => $data["trade"], "user" =>$data["user"]]);
        if($entity){
            $entity->setRating($data["rating"]);
            $entity->setReview($data["review"]);
            $entity->setDateModified($dateAdded);

        }else{
            $entity = new Rating();
            $entity->setTrade($data["trade"]);
            $entity->setUser($data["user"]);
            $entity->setRating($data["rating"]);
            $entity->setReview($data["review"]);
            $entity->setDateAdded($dateAdded);
            $entity->setDateModified($dateAdded);
        }


        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function prepareData($data)
    {
        $result = [];
        $result["user"] = $data["user"];
        $result["trade"] = $data["trade"];
        $result["rating"] = $data["rating"] ?? 0;
        $result["review"] = $this->filterMessage($data["review"]);

        return $result;
    }

    private function filterMessage($message)
    {
        $stripTasgs = new StripTags();
        $message = $stripTasgs->filter($message);

        $stringTrim = new StringTrim();
        $message = $stringTrim->filter($message);

        return $message;
    }

}