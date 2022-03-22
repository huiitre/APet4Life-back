<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="api_review")
     */
    public function index(): Response
    {
        return $this->render('api_review/index.html.twig', [
            'controller_name' => 'ApiReviewController',
        ]);
    }
}
