<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class TrackingRequestsRepository extends EntityRepository
{
    public function addRequest($site_name, $amount)
    {
        $this->createQueryBuilder("tr")
            ->update('Application\Entity\TrackingRequests', 'tr')
            ->set('tr.request', "tr.request + $amount")
            ->where("tr.name = '$site_name'")
            ->getQuery()
            ->execute();
    }

    public function checkIfExceededEtsyRequestLimit($site_name)
    {
        $query = $this->createQueryBuilder('tr')
            ->select("tr.date, tr.request")
            ->where("tr.name = :siteName")
            ->setParameter("siteName", $site_name);

        $result = $query->getQuery()->execute();

        $request_limit = 5000;

        if(($result[0]['date'])->format("Y-m-d") == date("Y-m-d") && $result[0]['request'] > ($request_limit - 126)) {
            return true;
        } else {
            return false;
        }
    }
}