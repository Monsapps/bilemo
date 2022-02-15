<?php
/**
 * Abstract class to share paginate function between multiple entity model
 */
namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

abstract class BasePaginateRepository extends ServiceEntityRepository
{

    protected function paginate(QueryBuilder $queryBuilder, int $limit, int $offset): Pagerfanta
    {

        if (0 == $limit) {
            throw new \LogicException('$limit must be greater than 0.');
        }

        $pager = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pager->setCurrentPage($offset);
        $pager->setMaxPerPage((int) $limit);
        
        return $pager;
    }

}