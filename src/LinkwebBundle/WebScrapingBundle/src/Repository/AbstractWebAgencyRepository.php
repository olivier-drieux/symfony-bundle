<?php

namespace App\LinkwebBundle\WebScrapingBundle\src\Repository;

use App\Entity\WebAgency;
use Doctrine\Persistence\ManagerRegistry;
use Linkweb\Repository\AbstractRepository;

/**
 * @method WebAgency|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebAgency|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebAgency[]    findAll()
 * @method WebAgency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method WebAgency|null findOneById($id)
 */
abstract class AbstractWebAgencyRepository extends AbstractRepository
{
    /**
     * WebAgencyRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebAgency::class);
    }
}
