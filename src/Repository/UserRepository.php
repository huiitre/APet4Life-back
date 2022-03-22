<?php

namespace App\Repository;

use App\Entity\Species;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /** 
    * @return User[] Renvoie un tableau d'objets utilisateurs de type 'Association'
    */
    public function findAllByAssociation()
    {

        $entityManager = $this->getEntityManager();

        $request = $entityManager->createQuery(
            "SELECT u 
            FROM App\Entity\User u
            WHERE u.type = 'Association'"
        );

        $resultats = $request->getResult();

        return $resultats;
    }

    /** 
    * @return User[] Renvoie un tableau d'objets utilisateurs de type 'Particulier'
    */
    public function findAllByParticular()
    {

        $entityManager = $this->getEntityManager();

        $request = $entityManager->createQuery(
            "SELECT u 
            FROM App\Entity\User u
            WHERE u.type = 'Particular'"
        );

        $resultats = $request->getResult();

        return $resultats;
    }

    /** 
    * @return User[] Renvoie un tableau d'objets d'un utilisateur via son slug et de type 'Association'
    */
    public function findOneAssociation($slug)
    {
        $entityManager = $this->getEntityManager();

        $request = $entityManager->createQuery(
            "SELECT u 
            FROM App\Entity\User u
            WHERE u.type = 'Association' AND u.slug = :slug");
            $request->setParameter('slug', $slug);

        $resultats = $request->getResult(); 

        return $resultats;
        
    }

    /** 
    * @return User[] Renvoie un tableau d'objets d'un utilisateur via sa localisation et de type 'Association'
    */
    public function findAllBySearch($geolocation = null, $responseLocation = null, $species = null)
    {
        $entityManager = $this->getEntityManager();

        /* 
        Ce que le front doit nous envoyer : 
        $geolocation = 'region' ou 'department' ou 'zipcode';
        $responseLocation = la valeur que l'input (choix utilisateur);
        $species = l'espèce choisi par l'utilisateur; */

        if (isset($geolocation, $responseLocation)){
        $request = $entityManager->createQuery(
            "SELECT u
            FROM App\Entity\User u
            WHERE u.type = 'Association' AND u.$geolocation = :responseLocation");

            $request->setParameter('responseLocation', $responseLocation);
        }

        $resultats = $request->getResult();

        return $resultats;
    }
}
