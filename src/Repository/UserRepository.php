<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

class UserRepository extends BasePaginateRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function search(?string $keyword, string $order, int $limit, int $offset, User $client = null): Pagerfanta
    {

        $queryBuilder = $this->createQueryBuilder('u')
        ->orderBy('u.username', $order);

        if($client !== null) {
            $queryBuilder
                ->andWhere('u.client = :client')
                ->setParameter('client', $client)
            ;

            //TODO Add search roles for admin
        }

        if ($keyword !== null) {
            $queryBuilder
                ->andWhere('u.username LIKE :keyword')
                ->setParameter('keyword', '%' . $keyword . '%')
            ;

        }

        return $this->paginate($queryBuilder, $limit, $offset);
    }


}