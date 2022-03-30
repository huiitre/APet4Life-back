<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Models\JsonError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\MySlugger;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
     * @Route("/api/")
     */
class ApiUserController extends AbstractController
{
    /**
     * @Route("user/associations", name="api_user_list_associations", methods={"GET"})
     */
    public function showAllAssociations(UserRepository $userRepository): Response
    {
        return $this->json(
            // les données à transformer en JSON
            $userRepository->findAllByAssociation(),
            // HTTP STATUS CODE
            200,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'list_associations']
        );
    }

    /**
     * @Route("user/association/{slug}", name="api_user_association", methods={"GET"})
     */
    public function showOneAssociation(UserRepository $userRepo, $slug): Response
    {
        $user = $userRepo->findOneAssociation($slug);

        return $this->json(
            // les données à transformer en JSON
            $user,
            // HTTP STATUS CODE
            200,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'association']
        );
    }

    /**
     * @Route("user/create", name="api_user_create", methods={"POST"})
     */
    public function createUser(EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, MySlugger $slugger, UserPasswordHasherInterface $hasher): Response
    {

        $data = $request->getContent();
        
        try {
            $newUser = $serializer->deserialize($data, User::class, 'json');
        } 
        catch (Exception $e) {
        return new JsonResponse("JSON invalide", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $errors = $validator->validate($newUser);
        if (count($errors) > 0) {

            $myJsonErrors = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvés");
            $myJsonErrors->setValidationErrors($errors);
        }

        if($newUser->getType() === 'Association'){
            $newUser->setRoles(['ROLE_ASSO']);
        } elseif ($newUser->getType() === 'Particular' || $newUser->getType() === 'Particulier') {
            $newUser->setRoles(['ROLE_USER']);
            $newUser->setPicture('https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png');
        } else if ($newUser->getType() === 'Administrateur') {
            $newUser->setRoles(['ROLE_ADMIN']);
        }
        
        $userHasher = $hasher->hashPassword($newUser, $newUser->getPassword());
        $newUser->setPassword($userHasher);

        $slug = $slugger->slugify($newUser->getName());
        $newUser->setSlug($slug);

        $doctrine->persist($newUser);

        try {
            
            $doctrine->flush();
        } 
        catch (UniqueConstraintViolationException $e) {
            return new JsonResponse("L'adresse mail existe déjà", Response::HTTP_CONFLICT);
            }

        return $this->json(
            // les données à transformer en JSON
            $newUser,
            // HTTP STATUS CODE
            Response::HTTP_CREATED,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'user']
        );
    }

    /**
     * @Route("user/search", name="api_user_search", methods={"GET"})
     */
    public function search(UserRepository $userRepo, Request $request, SerializerInterface $serializer): Response
    {

        $newSearch = $request->get('http://localhost:8080/api/user/search', [
            // these values are automatically encoded before including them in the URL
            'query' => [
                'geolocation' => $request->get('geolocation'),
                'responselocation' => $request->get('responselocation'),
                // 'species'=> $request->get('species'),
            ],
        ]);

        // $data = $request->get('geolocation');
        // $data2 = $request->get('responselocation');
        // dd($data2);
        // $newSearch =  $serializer->deserialize($data, Search::class, 'json');
        // dd($newSearch['query']['geolocation']);
        $geolocation = $newSearch['query']['geolocation'];
        $responseLocation = $newSearch['query']['responselocation'];
        // $species = $newSearch['query']['species'];
        
        return $this->json(
            $userRepo->findAllBySearch($geolocation, $responseLocation),
            200,
            [],
            ['groups' => 'search']
        );
    }

        /**
     * @Route("secure/user/update", name="api_user_update", methods={"PATCH"})
     */
    public function updateUser(EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $content = $request->getContent(); // Get json from request

        // $user = $userRepo->find($id); // Try to find product in database with provided id
        $user = $this->getUser();

        try {
            $user = $serializer->deserialize($content, User::class, 'json', ['object_to_populate' => $user]);
        } 
        catch (Exception $e) {
        return new JsonResponse("JSON invalide", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $errors = $validator->validate($user);
        if (count($errors) > 0) {

            $myJsonErrors = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvés");
            $myJsonErrors->setValidationErrors($errors);
        }

        /* $userHasher = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($userHasher); */

        $doctrine->persist($user);

        try {
            
            $doctrine->flush();
        } 
        catch (UniqueConstraintViolationException $e) {
            return new JsonResponse("L'adresse mail existe déjà", Response::HTTP_CONFLICT);
        }
            
        return $this->json(
            // les données à transformer en JSON
            $user,
            // HTTP STATUS CODE
            Response::HTTP_OK,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'user']
        );
    }

    /**
     * @Route("secure/user/delete", name="api_user_delete", methods={"DELETE"})
     */
    public function deleteUser(EntityManagerInterface $doctrine)
    {
        $user = $this->getUser();

        $doctrine->remove($user);
        $doctrine->flush();

        return $this->json(
            // les données à transformer en JSON
            // $user,
            // HTTP STATUS CODE
            Response::HTTP_NO_CONTENT,
            // HTTP headers supplémentaires
            // [],
            // Contexte de serialisation
            // ['groups' => 'user']
        );
    }

    /**
     * @Route("secure/user/profile", name="app_profil", methods={"GET"})
     */
    public function profile(): Response
    {
        //? pour la récupération de l'user via son token et pas l'id
        $user = $this->getUser();

        //? pas utile, j'ai ajouté le group 'user' dans l'entity user pour "role"
        // $roles = $user->getRoles();

        return $this->json(
            // les données à transformer en JSON
            $user,
            // HTTP STATUS CODE
            Response::HTTP_OK,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups' => 'user']
        );
    }

    //! ne sert pas dans le cadre de l'api
    /**
     * @Route("secure/user/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
