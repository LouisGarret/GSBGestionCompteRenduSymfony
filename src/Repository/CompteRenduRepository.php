<?php

namespace App\Repository;

use App\Entity\CompteRendu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CompteRendu|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteRendu|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteRendu[]    findAll()
 * @method CompteRendu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRenduRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompteRendu::class);
    }

    public function findByRegion($region)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.visiteur', 'v')
            ->where('v.region = :region')
            ->setParameter('region', $region)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findBySecteur($secteur)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.praticien', 'p')
            ->leftJoin('p.region','r')
            ->andWhere('r.secteur = :secteur')
            ->setParameter('secteur', $secteur)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByVisiteur($id)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.visiteur', 'v')
            ->andWhere('v.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByPraticien($id)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.praticien', 'p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function countByVisiteur(){
        return $this->createQueryBuilder('c')
            ->join('c.visiteur', 'v')
            ->select('v.username, COUNT(c) as nb')
            ->groupBy('v.username')
            ->getQuery()
            ->getResult();
    }
  
   public function countByVisiteurByDate($value, $value2){
        return $this->createQueryBuilder('c')
            ->join('c.visiteur', 'v')
            ->select('v.username, COUNT(c) as nb')
          	->where('c.dateVisite BETWEEN :val AND :val2')
          	->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->groupBy('v.username')
            ->getQuery()
            ->getResult();
    }

    public function countByDate($value, $value2){
        return $this->createQueryBuilder('c')
            ->join('c.visiteur', 'v')
            ->select('v.username, c.dateVisite')
          	->where('c.dateVisite BETWEEN :val AND :val2')
          	->setParameter('val', $value)
            ->setParameter('val2', $value2)
			->orderBy('c.dateVisite')
            ->getQuery()
            ->getResult();
    }

    public function countByVisiteurBySecteur($secteur){
		return $this->createQueryBuilder('c')
			->join('c.visiteur', 'v')
			->join('v.region', 'r')
			->select('v.username, COUNT(c) as nb')
			->where('r.secteur = :val')
			->setParameter('val', $secteur)
			->groupBy('v.username')
			->getQuery()
			->getResult();
	}

	public function countByVisiteurByRegion($region){
		return $this->createQueryBuilder('c')
			->join('c.visiteur', 'v')
			->join('v.region', 'r')
			->select('v.username, COUNT(c) as nb')
			->where('v.region = :val')
			->setParameter('val', $region)
			->groupBy('v.username')
			->getQuery()
			->getResult();
	}

    // /**
    //  * @return CompteRendu[] Returns an array of CompteRendu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompteRendu
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
