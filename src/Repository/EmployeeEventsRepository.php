<?php

namespace App\Repository;

use App\Entity\EmployeeEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EmployeeEvents|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeEvents|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeEvents[]    findAll()
 * @method EmployeeEvents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeEventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeEvents::class);
    }

    // /**
    //  * @return EmployeeEvents[] Returns an array of EmployeeEvents objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmployeeEvents
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /* 
    * Get Entry count by Event Id
    */
    
    public function findByEntryCount($event_id) {
         return $this->createQueryBuilder('ee')
             ->select('ee.entry', 'count(ee.id) as count')
             ->andWhere('ee.event_id = :val')
             ->setParameter('val', $event_id)
             ->groupBy('ee.entry')
             ->getQuery()
             ->getResult();
    }

}
