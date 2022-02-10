<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

class ProductRepository extends BasePaginateRepository
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

}
