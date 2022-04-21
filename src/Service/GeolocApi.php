<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/*
 * Classe d'accès à l'API d'omdbapi.com
 */

class GeolocApi
{
    // Les services nécessaires
    // On utilise le composant HttpClient de Symfony
    // @link https://symfony.com/doc/current/http_client.html
    private $httpClient;
    // Pour récupérer les paramètres de services.yaml (mais pas que !) depuis notre code
    // /!\ On pourrait tranmsettre la clé API directemnt via le constructeur comme fait précédemment
    // Autre façon de faire et qui permet d'accéder à tous les paramètres du conteneur de services
    // https://symfony.com/blog/new-in-symfony-4-1-getting-container-parameters-as-a-service
    private $parameterBag;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->parameterBag = $parameterBag;
    }

    /*
     * Renvoie le contenu JSON du film demandé
     * 
     * @param string $title Movie title
     */
    public function fetch($geoloc)
    {
        $uri = "https://geo.api.gouv.fr/" . $geoloc . "?fields=nom";
        $response = $this->httpClient->request('GET', $uri);

        // On récupère le contenu de la réponse
        $content = $response->getContent();
        // On convertit le JSON en tableau PHP
        $content = $response->toArray();
        
        $arrayLoc = [];
        foreach ($content as $ct) {
            $arrayLoc += [$ct['nom'] => $ct['nom']];
        }
        return $arrayLoc;
    }
}
