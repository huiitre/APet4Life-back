<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * Route qui permet de redigirer au site front depuis l'url racine
     * https://stackoverflow.com/questions/29747531/symfony-redirect-to-external-url
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->redirect('http://localhost:5050');
    }
}