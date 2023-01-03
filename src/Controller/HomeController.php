<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BienRepository;
use App\Repository\HeadersRepository;
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BienRepository $bienRepository,HeadersRepository $headersRepository): Response
    {
       // return $this->render('home/index.html.twig', [
         //   'controller_name' => 'HomeController',
        //]);
        $biens = $bienRepository->findByIsInHome(1);
        $slide = $headersRepository->findAll();
        return $this->render('home/index.html.twig', [
            'carousel' => true,  //Le caroussel ne s'affiche que sur la page d'accueil (voir base.twig)
            'top_products' => $biens,
            'headers' => $slide
        ]);
    }

    
}
