<?php

namespace App\DataFixtures\Provider;

class AssociationProvider
{
    // Taleau de 25 fausses associations pour les fixtures.
    private $association = [
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


    /**
     * Retourne un nom d'association au hasard
     */
    public function randAssociation()
    {
        return $this->association[array_rand($this->association)];
    }
}