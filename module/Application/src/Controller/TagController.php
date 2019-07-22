<?php

namespace Application\Controller;

use Application\Entity\Listing;
use Application\Entity\MainCategory;
use Application\Entity\Tag;
use Application\Form\ListingForm;
use Application\Service\ListingManager;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use RestApi\Controller\ApiController;

/**
 * Class TagController
 *
 * @method \Application\Repository\TagRepository getRepository()
 * @package Application\Controller
 */
class TagController extends ApiController
{

    /**
     * Entity manager
     * @var EntityManager
     */
    private $entityManager;

    public function searchAction()
    {
        $term = $this->params()->fromQuery('term');

        if(strlen($term) < 2){
            $this->httpStatusCode = 400;
            $this->apiResponse = [];
            return $this->createResponse();
        }

        $tags = $this->getEntityManager()->getRepository(Tag::class)->search($term);

        $tagsArr = array_reduce($tags??[], function ($carry, $tag){
            /** @var $tag Tag */
            $carry[] = $tag->getName();

            return $carry;
        }, []);

        $this->httpStatusCode = 200;
        $this->apiResponse = $tagsArr;
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
