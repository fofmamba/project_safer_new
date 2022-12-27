<?php

namespace App\Controller;

use App\Entity\SlideHome;
use App\Form\SlideHomeType;
use App\Repository\SlideHomeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/slide/home')]
class SlideHomeController extends AbstractController
{
    #[Route('/', name: 'app_slide_home_index', methods: ['GET'])]
    public function index(SlideHomeRepository $slideHomeRepository): Response
    {
        return $this->render('slide_home/index.html.twig', [
            'slide_homes' => $slideHomeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_slide_home_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SlideHomeRepository $slideHomeRepository): Response
    {
        $slideHome = new SlideHome();
        $form = $this->createForm(SlideHomeType::class, $slideHome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slideHomeRepository->save($slideHome, true);

            return $this->redirectToRoute('app_slide_home_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slide_home/new.html.twig', [
            'slide_home' => $slideHome,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_slide_home_show', methods: ['GET'])]
    public function show(SlideHome $slideHome): Response
    {
        return $this->render('slide_home/show.html.twig', [
            'slide_home' => $slideHome,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_slide_home_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SlideHome $slideHome, SlideHomeRepository $slideHomeRepository): Response
    {
        $form = $this->createForm(SlideHomeType::class, $slideHome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slideHomeRepository->save($slideHome, true);

            return $this->redirectToRoute('app_slide_home_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slide_home/edit.html.twig', [
            'slide_home' => $slideHome,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_slide_home_delete', methods: ['POST'])]
    public function delete(Request $request, SlideHome $slideHome, SlideHomeRepository $slideHomeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slideHome->getId(), $request->request->get('_token'))) {
            $slideHomeRepository->remove($slideHome, true);
        }

        return $this->redirectToRoute('app_slide_home_index', [], Response::HTTP_SEE_OTHER);
    }
}
