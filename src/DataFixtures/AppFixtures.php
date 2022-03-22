<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\AnimalProvider;
use App\DataFixtures\Provider\AssociationProvider;
use App\DataFixtures\Provider\RegionProvider;
use App\DataFixtures\Provider\SpeciesProvider;
use App\Entity\Animal;
use App\Entity\Species;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory as Faker;
use Faker\Provider\Address;
use App\Service\MySlugger;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $connexion;
    private $hasher;
    private $slugger;

    public function __construct(Connection $connexion, UserPasswordHasherInterface $hasher, MySlugger $mySlugger)
    {
        $this->connexion = $connexion;
        $this->hasher = $hasher;
        $this->slugger = $mySlugger;
    }

    private function truncate()
    {
        // On désactive la vérification des FK
        // Sinon les truncate ne fonctionne pas.
        $this->connexion->executeQuery('SET foreign_key_checks = 0');

        // La requete TRUNCATE remet l'auto increment à 1
        $this->connexion->executeQuery('TRUNCATE TABLE species');
        $this->connexion->executeQuery('TRUNCATE TABLE user');
        $this->connexion->executeQuery('TRUNCATE TABLE animal');
    }
    public function load(ObjectManager $manager): void
    {
        $this->truncate();

        $faker = Faker::create('fr_FR');

        $associationProvider = new AssociationProvider();
        $speciesProvider = new SpeciesProvider();
        $regionProvider = new RegionProvider();
        $animalProvider = new AnimalProvider();

        /* ============== SPECIES ==============  */
        $allSpeciesEntity = [];
        // Tableau des espèces
        $species = [
            'Chat',
            'Chien',
            'Lapin',
            'Cheval',
            'Rongeur',
            'Serpent',
        ];

        foreach ($species as $espèce) {
            $newSpecies = new Species;
            $newSpecies->setName($espèce);

            $allSpeciesEntity[] = $newSpecies;
            $manager->persist($newSpecies);
        }
        /* ============== GEOLOCATION ==============  */
        $dataGeolocation = [
            [69001, 'Lyon', 'Rhône', 'Auvergne-Rhône-Alpes'],
            [39100, 'Dole', 'Jura', 'Bourgogne-Franche-Comté'],
            [29200, 'Brest', 'Finistère', 'Bretagne'],
            [45250, 'Briare', 'Loiret', 'Centre-Val de Loire'],
            [20000, 'Ajaccio', 'Corse-du-Sud', 'Corse'],
            [20600, 'Bastia', 'Haute-Corse', 'Corse'],
            [57000, 'Metz', 'Moselle', 'Grand Est'],
            [62000, 'Arras', 'Pas-de-Calais', 'Hauts-de-France'],
            [93370, 'Montfermeil', 'Seine-Saint-Denis', 'Ile-de-France'],
            [14118, 'Caen', 'Calvados', 'Normandie'],
            [86000, 'Poitiers', 'Vienne', 'Nouvelle-Aquitaine'],
            [46500, 'Rocamadour', 'Lot', 'Occitanie'],
            [44190, 'Clisson', 'Loire-Atlantique', 'Pays de la Loire'],
            [85600, 'Montaigu', 'Vendée', 'Pays de la Loire'],
            [06000, 'Nice', 'Alpes-Maritimes', 'Provence-Alpes-Côte d’Azur'],
            [97100, 'Basse-Terre', 'Guadeloupe', 'Guadeloupe'],
            [97200, 'Fort-de-France', 'Martinique', 'Martinique'],
            [97300, 'Cayenne', 'Guyane', 'Guyane'],
            [97400, 'Saint-Denis', 'La Réunion', 'La Réunion'],
            [97611, 'Mamoudzou', 'Mayotte', 'Mayotte'],
        ];

        /* ============== USER ==============  */

        $allUserEntity = [];
        $allAssociationsEntity = [];
        $allParticularEntity = [];

        $associations = [
            'Ass\'O Pet',
            'Adopte un Pet',
            'Adopte une patte',
            'Get A Pet',
            'Carapatte',
            'Find Your Pet',
            'Anim\' Assoc',
            'Save a Pet',
            'Ton futur Pet',
            'A Pet For Life',
            'Donne moi ta patte',
            'A ton Poney',
            'Les Animaux Pottés',
            'L\'arche de Noé',
            'SOS Animaux',
            '1000 Moustaches',
            'Mission Adoption',
            'Arist\' O\'Chats',
            'Aidofélins',
            'Les Grosses Patounes',
            'Hop, hop, hop on adopte !',
            'Les Griffes du Coeur',
            'Les Petites Patounes',
            'Meetic Pets',
            'Meet Ton Pet'
        ];

        // Création des associations: on parcourt le tableau et on leur donne des informations grâce à faker et d'autres que l'on a fait à la main.
        foreach ($associations as $association) {
            $user = new User;

            $type = 'Association';
            $user->setType($type);

            $randDataGeolocation = mt_rand(0, count($dataGeolocation) - 1);

            $user->setName($association);
            $user->setSiret($faker->siret());
            $user->setAdress($faker->streetAddress());
            $user->setZipcode($dataGeolocation[$randDataGeolocation]['0']);
            $user->setCity($dataGeolocation[$randDataGeolocation]['1']);
            $user->setDepartment($dataGeolocation[$randDataGeolocation]['2']);
            $user->setRegion($dataGeolocation[$randDataGeolocation]['3']);
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setDescription($faker->text());
            $status = rand(1, 2) == 1 ? 'true' : 'false';
            $user->setStatus($status);
            $user->setPicture(' https://placekitten.com/500/' . mt_rand(500, 600));

            $slug = $this->slugger->slugify($user->getName());
            $user->setSlug($slug);

            $user->setMail($user->getSlug() . '@exemple.com');
            $user->setWebsite('https:://fake-' . $user->getSlug() . '.com');

            // On hash le mot de passe avec un composant de symfony pour pas qu'il n'apparaisse en clair
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            $user->setRoles(['ROLE_ASSO']);

                // On donne à chaque association, un nombre random d'espèce à proposer (entre 1 et 3)
                $allSpeciesAssocEntity = [];
                for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                    $randomSpecies = $allSpeciesEntity[mt_rand(0, count($allSpeciesEntity) - 1)];
                    $user->addSpecies($randomSpecies);
                    $allSpeciesAssocEntity[] = $randomSpecies;
                }

                /* ============== ANIMALS ==============  */
                $allAnimalsEntity = [];
                // On donne à chaque association un nombre random d'animal (entre 1 et 10) à proposer
                for ($a = 1; $a <= mt_rand(1, 10); $a++) {

                    $animal = new Animal;

                    // On pioche aléatoirement dans un fichier "provider" qu'on a fait à la main, des noms d'animaux dans une liste.
                    $animal->setName($animalProvider->randAnimal());

                    $sexe = rand(1, 2) == 1 ? 'Female' : 'Male';
                    $animal->setSexe($sexe);

                    $animal->setDescription($faker->text());

                    $status = rand(1, 3);
                    switch ($status) {
                        case 1:
                            "junior";
                            break;
                        case 2:
                            "adulte";
                            break;
                        case 3:
                            "senior";
                            break;
                    }
                    $animal->setStatus($status);

                    $randomSpecies = $allSpeciesAssocEntity[mt_rand(0, count($allSpeciesAssocEntity) - 1)];
                    $animal->setSpecies($randomSpecies);

                    $allAnimalsEntity[] = $animal;
                    $user->addAnimal($animal);

                    $manager->persist($animal);
                }

            $allAssociationsEntity[] = $user;
            $manager->persist($user);
        }

        // Création des particuliers: on crée 25 fausses données de particulier grâce à faker
        for ($i = 1; $i <= 25; $i++) {
            $user = new User;
            $randDataGeolocation = mt_rand(0, count($dataGeolocation) - 1);

            $type = 'Particular';
            $user->setType($type);
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setDepartment($dataGeolocation[$randDataGeolocation]['2']);
            $user->setPicture('https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png');

            $user->setMail($user->getFirstname() . $user->getLastname() . '@exemple.com');

            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            $status = rand(1, 2) == 1 ? 'true' : 'false';
            $user->setStatus($status);

            $user->setRoles(['ROLE_USER']);
            $allParticularEntity[] = $user;
            $manager->persist($user);
        }

        // On crée un profil administrateur, pour la gestion du back-office
        $users = [
            [
                'login' => 'admin@admin.com',
                'password' => 'admin',
                'roles' => 'ROLE_ADMIN',
            ]
        ];

        foreach ($users as $currentUser) {
            $newUser = new User();
            $type = 'Administrateur';
            $newUser->setType($type);
            $newUser->setMail($currentUser['login']);
            $newUser->setRoles([$currentUser['roles']]);
            $newUser->setDepartment('null');

            $hashedPassword = $this->hasher->hashPassword(
                $newUser,
                $currentUser['password']
            );
            $newUser->setPassword($hashedPassword);

            $manager->persist($newUser);
        }

        // les reviews ne sont pas encore faites.
        /* ============== REVIEWS ==============  */
        /* for($i = 0; $i < 100; $i ++) {
        
        } */

        // On envoi les données créées en bdd
        $manager->flush();
    }
}
