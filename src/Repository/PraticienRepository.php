<?php

namespace App\Repository;

use App\Entity\Praticien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Praticien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Praticien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Praticien[]    findAll()
 * @method Praticien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PraticienRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Praticien::class);
    }

	public function findAllPraticien(): ?Praticien
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p')
			->orderBy('p.nom', 'ASC')
			;
		return $qb;
	}

    // /**
    //  * @return Praticien[] Returns an array of Praticien objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Praticien
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
