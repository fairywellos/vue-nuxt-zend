<?php

namespace Application\Controller;

use Application\Entity\MainCategory;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;

/**
 * Class MainCategoryController
 *
 * @package Application\Controller
 */
class MainCategoryController extends ApiController
{

    /**
     * Entity manager
     * @var EntityManager
     */
    private $entityManager;

    public function indexAction()
    {
        $categories = $this->getEntityManager()->getRepository(MainCategory::class)->findAll();

        $categoriesArr = array_reduce($categories??[], function ($carry, $category){
            /** @var $category MainCategory */
            $categoryArr = $category->toArray();
            unset($categoryArr['listings'], $categoryArr['tags']);

            $carry[] = $categoryArr;

            return $carry;
        }, []);

        $this->httpStatusCode = 200;
        $this->apiResponse = $categoriesArr;
        return $this->createResponse();
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
}
