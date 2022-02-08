<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class ProductRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function search(?string $keyword, string $order, int $limit, int $offset): Pagerfanta
    {

        $queryBuilder = $this->createQueryBuilder('p')
        ->orderBy('p.name', $order);

        if ($keyword !== null) {
            $queryBuilder
                ->where('p.name LIKE :keyword')
                ->setParameter('keyword', '%' . $keyword . '%')
            ;

        }

        return $this->paginate($queryBuilder, $limit, $offset);
    }

    private function paginate(QueryBuilder $queryBuilder, int $limit, int $offset): Pagerfanta
    {
        $pager = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pager->setCurrentPage($offset);
        $pager->setMaxPerPage((int) $limit);
        
        return $pager;
    }

}
