<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\MyDataService;

class PartialsController extends AbstractController
{

    public function index(MyDataService $myDataService): Response
    {
        $data = $myDataService->getData();
        dd($data);
        return $this->render('partials/index.html.twig', [
            'data' => $data,
        ]);
    }
}
