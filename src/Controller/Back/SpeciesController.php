<?php

namespace App\Controller\Back;

use App\Entity\Species;
use App\Form\SpeciesType;
use App\Repository\SpeciesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/species")
 */
class SpeciesController extends AbstractController
{
    /**
     * Route qui permet de récupérer la liste des espèces
     * @Route("/", name="app_all_species", methods={"GET"})
     */
    public function allSpecies(SpeciesRepository $speciesRepository): Response
    {
        return $this->render('back/species/index.html.twig', [
            'species' => $speciesRepository->findAll(),
        ]);
    }

    /**
     * Route qui permet de créer une espèce
     * @Route("/new", name="app_species_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $species = new Species();
        $form = $this->createForm(SpeciesType::class, $species);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($species);
            $entityManager->flush();

            return $this->redirectToRoute('app_all_species', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/species/new.html.twig', [
            'species' => $species,
            'form' => $form,
        ]);
    }

    /**
     * Route qui permet de récupérer les informations liées à une espèce
     * @Route("/{id}", name="app_species_show", methods={"GET"})
     */
    public function show(Species $species): Response
    {
        return $this->render('back/species/show.html.twig', [
            'species' => $species,
        ]);
    }

    /**
     * Route qui permet de modifier une espèce
     * @Route("/{id}/edit", name="app_species_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Species $species, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpeciesType::class, $species);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_all_species', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/species/edit.html.twig', [
            'species' => $species,
            'form' => $form,
        ]);
    }

    /** Route qui permet de supprimer une espèce
     * @Route("/{id}", name="app_species_delete", methods={"POST"})
     */
    public function delete(Request $request, Species $species, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$species->getId(), $request->request->get('_token'))) {
            $entityManager->remove($species);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_all_species', [], Response::HTTP_SEE_OTHER);
    }
}
