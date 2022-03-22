<?php

namespace App\Repository;

use App\Entity\Species;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Species|null find($id, $lockMode = null, $lockVersion = null)
 * @method Species|null findOneBy(array $criteria, array $orderBy = null)
 * @method Species[]    findAll()
 * @method Species[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpeciesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Species::class);
    }

    // /**
    //  * @return Species[] Returns an array of Species objects
    //  */
    public function findAll()
    {

        $entityManager = $this->getEntityManager();

        $request = $entityManager->createQuery(
            "SELECT s.id, s.name
            FROM App\Entity\Species s"
        );

        $resultats = $request->getResult();

        return $resultats;
    }

    /*
    public function findOneBySomeField($value): ?Species
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
