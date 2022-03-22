<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\MySlugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserSlugifyCommand extends Command
{
    protected static $defaultName = 'app:user:slugify';
    protected static $defaultDescription = 'For slugify the association name';

    // Pour interagir avec les utilisateurs dans la BDD
    private $userRepository;
    // Pour pouvoir utiliser MySlugger
    private $mySlugger;
    // Pour interagir avec l'entity manager
    private $entityManager;

    /**
     * @param UserRepository $userRepository
     * @param MySlugger $mySlugger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct (UserRepository $userRepository, MySlugger $mySlugger, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->mySlugger = $mySlugger;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {

    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // j'utilise une classe qui permet de faire des entrées/sorties 
        $io = new SymfonyStyle($input, $output);

        $io->info('Début du sluggify');
        
        // Récupérer tous les utilisateurs
        $associations = $this->userRepository->findAllByAssociation();

        // créer le slug à partir du name des utilisateurs
        foreach ($associations as $association) {
            $io->info('Name à Slugifier : ' . $association->getName());

            // On slugifie le name avec notre service MySlugger
            $association->setSlug($this->mySlugger->slugify($association->getName()));

            $io->info('Résultat : ' . $association->getSlug());
        }

        // enregistrer le slug
        $this->entityManager->flush();

        $io->info('Terminé');
        return Command::SUCCESS;

    }
}