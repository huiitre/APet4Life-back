<?php

namespace App\Controller\Api;

use App\Repository\SpeciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiSpeciesController extends AbstractController
{
    /**
     * @Route("/api/species", name="api_species")
     */
    public function allSpecies(SpeciesRepository $species): Response
    {
        return $this->json(
            // les données à transformer en JSON
            $species->findAll(),
            // HTTP STATUS CODE
            200,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'list_species']
        );
    }
}
