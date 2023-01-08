<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BienRepository;
use App\Repository\HeadersRepository;
use App\Repository\CategoryRepository;
use App\Service\MyDataService;
class HomeController extends AbstractController
{
    public function header(MyDataService $myDataService)
    {
        $data = $myDataService->getData();
        return $this->render('home/index.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/', name: 'app_home')]
    public function index(BienRepository $bienRepository,HeadersRepository $headersRepository,CategoryRepository $categoryRepository): Response
    {
       // return $this->render('home/index.html.twig', [
         //   'controller_name' => 'HomeController',
        //]);
        $biens = $bienRepository->findByIsInHome(1);
        $slide = $headersRepository->findAll();
        $randon = $bienRepository->findAll();
        $categories= $categoryRepository->findAll();
        shuffle($randon);
        $limitedItems = array_slice($randon, 0, 3);
        return $this->render('home/index.html.twig', [
            'carousel' => true,  //Le caroussel ne s'affiche que sur la page d'accueil (voir base.twig)
            'top_products' => $biens,
            'headers' => $slide,
            'shuffe' =>$limitedItems,
            'categories' =>$categories,
        ]);
    }

    
}
