<?php

namespace App\DataFixtures\Provider;

class SpeciesProvider
{
    // Taleau de 6 espèces d'animaux pour les Fixtures.
    private $species = [
        'Chat',
        'Chien',
        'Lapin',
        'Cheval',
        'Rongeur',
        'Serpent',
    ];

    /**
     * Retourne un nom d'espèce au hasard
     */
    public function randSpecies()
    {
        return $this->species[array_rand($this->species)];
    }
}