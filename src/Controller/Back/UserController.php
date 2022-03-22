<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\AssoType;
use App\Form\PartType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\MySlugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/user")
 */
class UserController extends AbstractController
{
    /**
     * Route qui récupère la liste de toutes les associations
     * @Route("/associations", name="app_back_asso", methods={"GET"})
     */
    public function association(UserRepository $userRepository): Response
    {
        return $this->render('back/user/asso.html.twig', [
            'users' => $userRepository->findAllByAssociation(),
        ]);
    }

    /**
     * Route qui récupère la liste des particuliers
     * @Route("/particuliers", name="app_back_particular", methods={"GET"})
     */
    public function particular(UserRepository $userRepository): Response
    {
        return $this->render('back/user/particular.html.twig', [
            'users' => $userRepository->findAllByParticular(),
        ]);
    }

    /**
     * Route qui permet de créer une association
     * @Route("/new/asso", name="app_back_asso_new", methods={"GET", "POST"})
     */
    public function newAsso(Request $request, EntityManagerInterface $entityManager, MySlugger $slugger, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(AssoType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slugify($user->getName());
            $user->setSlug($slug);

            $user->setType('Association');

            $userHasher = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($userHasher);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_asso', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new-asso.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Route qui permet de créer un particulier
     * @Route("/new/particulier", name="app_back_part_new", methods={"GET", "POST"})
     */
    public function newPart(Request $request, EntityManagerInterface $entityManager, MySlugger $slugger, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(PartType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Si le formulaire est envoyé et validé, je peux mettre des valeurs par défaut pour certaines propriétés avant d'envoyer les données en base de données
            $user->setType('Particular');

            if($user->getType() === 'Association'){
                $user->setRoles(['ROLE_ASSO']);
            } elseif ($user->getType() === 'Particular' || $user->getType() === 'Particulier') {
                $user->setRoles(['ROLE_USER']);
                $user->setPicture('https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png');
            } else if ($user->getType() === 'Administrateur') {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $userHasher = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($userHasher);

            // Enregistre les données du formulaire
            $entityManager->persist($user);
            // Envoi les nouvelles informations en base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_back_particular', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new-part.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Route qui récupère les informations d'une association par id
     * @Route("/asso/{id}", name="app_back_user_show_asso", methods={"GET"})
     */
    public function showUser(User $user): Response
    {
        return $this->render('back/user/show-asso.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Route qui récupère les informations d'un particulier par id
     * @Route("/part/{id}", name="app_back_user_show_part", methods={"GET"})
     */
    public function showPart(User $user): Response
    {
        return $this->render('back/user/show-part.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Route qui permet de modifier une ou plusieurs informations d'une association
     * @Route("/{id}/edit/asso", name="app_back_asso_edit", methods={"GET", "POST"})
     */
    public function editAsso(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssoType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_asso', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit-asso.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Route qui permet de modifier une ou plusieurs informations d'un particulier
     * @Route("/{id}/edit/particulier", name="app_back_part_edit", methods={"GET", "POST"})
     */
    public function editPart(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_particular', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit-part.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Route qui permet de supprimer une association
     * @Route("/{id}/asso", name="app_back_asso_delete", methods={"POST"})
     */
    public function deleteAsso(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_asso', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Route qui permet de supprimer un particulier
     * @Route("/{id}/particulier", name="app_back_part_delete", methods={"POST"})
     */
    public function deletePart(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_particular', [], Response::HTTP_SEE_OTHER);
    }
}
