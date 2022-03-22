<?php

namespace App\Controller\Api;

use App\DataFixtures\Provider\AnimalProvider;
use App\Entity\Animal;
use App\Entity\Species;
use App\Entity\User;
use App\Models\JsonError;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/animal")
 */
class ApiAnimalController extends AbstractController
{
    /**
     * @Route("s/", name="api_list_animal")
     */
    public function showAllAnimals(AnimalRepository $animalRepository): Response
    {
        return $this->json(
            // les données à transformer en JSON
            $animalRepository->findAll(),
            // HTTP STATUS CODE
            200,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'list_animal']
        );
    }

    /**
     * @Route("/{id}", name="api_animal", methods={"GET"})
     */
    public function showOneAnimal(Animal $animal): Response
    {

        return $this->json(
            // les données à transformer en JSON
            $animal,
            // HTTP STATUS CODE
            200,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'animal']
        );
    }

    /**
     * @Route("/create", name="api_animal_create", methods={"POST"})
     */
    public function createAnimal(EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {

        $data = $request->getContent();

        try {
            $newAnimal = $serializer->deserialize($data, Animal::class, 'json'); 
        } 
        catch (Exception $e) {
        return new JsonResponse("JSON invalide", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $errors = $validator->validate($newAnimal);
        if (count($errors) > 0) {

            $myJsonErrors = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvés");
            $myJsonErrors->setValidationErrors($errors);
        }

        $doctrine->persist($newAnimal);
        $doctrine->flush();

        return $this->json(
            // les données à transformer en JSON
            $newAnimal,
            // HTTP STATUS CODE
            Response::HTTP_CREATED,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'animal']
        );
    }

    /**
     * @Route("/update/{id}", name="api_animal_update", methods={"PATCH"})
     */
    public function updateAnimal(EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, AnimalRepository $animalRepo, int $id)
    {
        $content = $request->getContent(); // Get json from request

        $animal = $animalRepo->find($id); // Try to find product in database with provided id
    
        try {
            $animal = $serializer->deserialize($content, Animal::class, 'json', ['object_to_populate' => $animal]);
        } 
        catch (Exception $e) {
        return new JsonResponse("JSON invalide", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $errors = $validator->validate($animal);
        if (count($errors) > 0) {

            $myJsonErrors = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvés");
            $myJsonErrors->setValidationErrors($errors);
        }

        $doctrine->persist($animal);
        //dd($DataToUpdate);
        $doctrine->flush();

        return $this->json(
            // les données à transformer en JSON
            $animal,
            // HTTP STATUS CODE
            Response::HTTP_OK,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'animal']
        );
    }

    /**
     * @Route("/delete/{id}", name="api_animal_delete", methods={"DELETE"})
     */
    public function deleteAnimal(EntityManagerInterface $doctrine, AnimalRepository $animalRepo, $id)
    {
        $animal = $animalRepo->find($id);

        $doctrine->remove($animal);
        $doctrine->flush();

        return $this->json(
            // les données à transformer en JSON
            $animal,
            // HTTP STATUS CODE
            Response::HTTP_OK,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'animal']
        );
    }

}
