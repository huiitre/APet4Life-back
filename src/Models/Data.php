<?php
namespace App\Models;

class Data
{
    // Donnée nécessaires pour l'accueil
    private $data = [

        [
            "category_name" => "Associations",
            "picture" => "https://cdn.pixabay.com/photo/2021/04/12/06/38/pet-shop-6171565_960_720.png",
            "description" => "Pour ajouter, modifer ou supprimer des associations.",
            "path" => "/back/user/associations",
        ],

        [
            "category_name" => "Particuliers",
            "picture" => "https://cdn.pixabay.com/photo/2019/07/07/17/48/avatar-4322968_960_720.png",
            "description" => "Pour ajouter, modifer ou supprimer les particuliers.",
            "path" => "/back/user/particuliers",
        ],

        [
            "category_name" => "Espèces",
            "picture" => "https://img.freepik.com/vecteurs-libre/groupe-animaux-mignons-isole-fond-blanc_1308-46314.jpg?t=st=1646929796~exp=1646930396~hmac=e9e418625e18a8582ec3029da354f119151c63741d720cb579cc6e9bc37b2e66&w=1060",
            "description" => "Pour ajouter, modifer ou supprimer les espèces.",
            "path" => "/back/species",
        ],
    ];

    public function getAllData()
    {
        return $this->data;
    }
}