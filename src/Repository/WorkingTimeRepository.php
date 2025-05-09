<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\WorkingTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkingTime>
 */
class WorkingTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkingTime::class);
    }

    public function findForUserInDateRange(User $user, \DateTime $startDate, \DateTime $endDate) : array
    {
        return $this->createQueryBuilder('wt')
            ->andWhere('wt.user = :user')
            ->andWhere('wt.startDate BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $startDate->format('Y-m-d'))
            ->setParameter('end', $endDate->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }
}
