<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use App\Service\UploaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\SlideHome;
use App\Form\SlideHomeType;
use App\Repository\SlideHomeRepository;


#[Route('/')]
class BienController extends AbstractController
{
    #[Route('/', name: 'app_bien_index', methods: ['GET'])]
    public function index(BienRepository $bienRepository,SlideHomeRepository $slideHomeRepository): Response
    { return $this->render('bien/index.html.twig', [
            'biens' => $bienRepository->findAll(),
            'slide' =>$slideHomeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BienRepository $bienRepository, SluggerInterface $slugger,UploaderService $uploaderService,): Response
    {
        
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                        /** @var UploadedFile $brochureFile */
                        $imageFile = $form->get('photo')->getData();

                        // this condition is needed because the 'brochure' field is not required
                        // so the PDF file must be processed only when a file is uploaded
                        if ($imageFile) {
                            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                            // this is needed to safely include the file name as part of the URL
                            $safeFilename = $slugger->slug($originalFilename);
                            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
                            // Move the file to the directory where brochures are stored
                            try {
                                $imageFile->move(
                                    $this->getParameter('biens_directory'),
                                    $newFilename
                                );
                            } catch (FileException $e) {
                                // ... handle exception if something happens during file upload
                            }
            
                            // updates the 'brochureFilename' property to store the PDF file name
                            // instead of its contents
                            $bien->setImage($newFilename);
                        }
            $bienRepository->save($bien, true);

            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_show', methods: ['GET'])]
    public function show(Bien $bien): Response
    {
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $bienRepository->save($bien, true);

            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/edit.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_delete', methods: ['POST'])]
    public function delete(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            $bienRepository->remove($bien, true);
        }

        return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
    }
}
