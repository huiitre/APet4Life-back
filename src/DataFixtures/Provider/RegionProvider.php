<?php

namespace App\DataFixtures\Provider;

class RegionProvider
{
    // Taleau de 25 régions pour les Fixtures.

    private $region = [
      "Guadeloupe",
      "Martinique",
      "Guyane",
      "La Réunion",
      "Mayotte",
      "Île-de-France",
      "Centre-Val de Loire",
      "Bourgogne-Franche-Comté",
      "Normandie",
      "Hauts-de-France",
      "Grand Est",
      "Pays de la Loire",
      "Bretagne",
      "Nouvelle-Aquitaine",
      "Occitanie",
      "Auvergne-Rhône-Alpes",
      "Provence-Alpes-Côte d'Azur",
      "Corse",
    ];

    /**
     * Retourne un nom d'une region au hasard
     */
    public function randRegion()
    {
        return $this->region[array_rand($this->region)];
    }
}