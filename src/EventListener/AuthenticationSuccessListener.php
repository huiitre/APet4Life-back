<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    /**
     * Permet d'envoyer des informations à l'authentification d'un utilisateur
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        $userEnt = $event->getUser();
        //dd($user);

        if (!$user instanceof UserInterface) {
            return;
        }
        if (!$userEnt instanceof User) {
            return;
        }

        $data['data'] = array(
            //? anciennement 'username', pour garder la même chose qu'avec le retour de la route /profile
            'mail' => $user->getUserIdentifier(),
            //? --------------------------------
            'roles' => $user->getRoles(),
            'id' => $userEnt->getId(),
            'type' => $userEnt->getType(),
            'name' => $userEnt->getName(),
            'firstname' => $userEnt->getFirstname(),
            'lastname' => $userEnt->getLastName(),
            'siret' => $userEnt->getSiret(),
            'adress' => $userEnt->getAdress(),
            'zipcode' => $userEnt->getZipcode(),
            'city'       => $userEnt->getCity(),
            'department' => $userEnt->getDepartment(),
            'region' => $userEnt->getRegion(),
            'phone_number' => $userEnt->getPhoneNumber(),
            'description' => $userEnt->getDescription(),
            'picture' => $userEnt->getPicture(),
            'website' => $userEnt->getWebsite(),
        );

        $event->setData($data);
    }
}