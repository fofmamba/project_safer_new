<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatidtiqueController extends AbstractController
{
    #[Route('/statidtique', name: 'app_statidtique')]
    public function index(): Response
    {
        return $this->render('statidtique/index.html.twig', [
            'controller_name' => 'StatidtiqueController',
        ]);
    }
}
