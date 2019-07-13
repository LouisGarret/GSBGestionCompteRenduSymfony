<?php

namespace App\Repository;

use App\Entity\TypePraticien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypePraticien|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePraticien|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePraticien[]    findAll()
 * @method TypePraticien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePraticienRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypePraticien::class);
    }

    // /**
    //  * @return TypePraticien[] Returns an array of TypePraticien objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypePraticien
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
