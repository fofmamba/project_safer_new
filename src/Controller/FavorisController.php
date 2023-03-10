<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Category;
use App\Form\Favoris1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bien;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BienRepository;
use App\Repository\CategoryRepository;
use App\Repository\FavorisRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
#[Route('/favoris')]
class FavorisController extends AbstractController
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager,RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }


    #[Route('/', name: 'app_favoris_index', methods: ['GET'])]
    public function index(FavorisRepository $favorisRepository,BienRepository $bienRepository,CategoryRepository $categoryRepository): Response
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get("panier", []);
        $categories= $categoryRepository->findAll();
        $dataPanier = [];
        $total = 0;
        $cpt =0;
        foreach ($panier as $id => $quantite) {
            $product = $bienRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $cpt +=1;
            $total += $product->getPrix();
        }
        return $this->render(
            'favoris/index.html.twig',
            compact("dataPanier", "total","categories")
        );
    }

    #[Route('/add/{id}', name: 'add_to_cart', methods: ['GET', 'POST'])]
    public function add(Bien $bien)
    {
        $session = $this->requestStack->getSession();
        // On r??cup??re le panier actuel
        $cpt=0;
        $favoris = $session->get("panier", []);
        $id = $bien->getId();
        $idcat = $bien->getCategoryid();
        if (empty($favoris[$id])) {
            $favoris[$id] = 1;
            $cpt +=1;
        } else {
            $favoris[$id] = 1;
        }
        $session->set("panier", $favoris);
        return $this->redirectToRoute('show_bien_by_categorie', ['id' => $idcat]);
    }

    #[Route('/delete', name: 'delete_all')]
    public function deleteAll()
    {
        $session = $this->requestStack->getSession();
        $session->remove("panier");

        return $this->redirectToRoute("app_favoris_index");
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete_panier(Bien $bien)
    {
        $session = $this->requestStack->getSession();
        // On r??cup??re le panier actuel
        $panier = $session->get("panier", []);
        $id = $bien->getId();

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_favoris_index");
    }

    #[Route('/new', name: 'app_favoris_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FavorisRepository $favorisRepository,BienRepository $bienRepository,MailerInterface $mailer,CategoryRepository $categoryRepository): Response
    {
        $favori = new Favoris();
        $form = $this->createForm(Favoris1Type::class, $favori);
        $form->handleRequest($request);
        $sessio = new Session();
        $session = $this->requestStack->getSession();
        $panier = $session->get("panier", []);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $emaile = (new TemplatedEmail());
            $cle=array_keys($panier);
            foreach ($cle as $id) {
                $product = $bienRepository->find($id);
                $favori = new Favoris();
                $favori->setIdBien($product->getId());
                $favori->setImage($product->getImage());
                $favori->setBienprix($product->getPrix());
                $email = $form['email']->getData();
                $numero = $form['numero']->getData();
                $favori->setEmail($email);
                $favori->setNumero($numero);
                //cr??ation de mail
                $this->entityManager->persist($favori);
                $this->entityManager->flush();
                }
                $emaile->from('mambamakagbe@gmail.com');
                $emaile->to('kyria.agbodouamenou@gmail.com');
                $emaile->subject('Welcome to the Space Bar!');
                //envoi de mail
                $mailer->send($emaile);
                //confirmaation dee l'envoi du mail et redirection
            $sessio->getFlashBag()->add('success', 'Envoyer avec succ??s');
            $session->remove("panier");
            return $this->redirectToRoute('app_favoris_index', compact('sessio'), Response::HTTP_SEE_OTHER);
        }     
        return $this->renderForm('favoris/new.html.twig', [
            'favori' => $favori,
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_favoris_show', methods: ['GET'])]
    public function show(Favoris $favori): Response
    {
        return $this->render('favoris/show.html.twig', [
            'favori' => $favori,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_favoris_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Favoris $favori, FavorisRepository $favorisRepository): Response
    {
        $form = $this->createForm(Favoris1Type::class, $favori);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $favorisRepository->save($favori, true);

            return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('favoris/edit.html.twig', [
            'favori' => $favori,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favoris_delete', methods: ['POST'])]
    public function delete(Request $request, Favoris $favori, FavorisRepository $favorisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favori->getId(), $request->request->get('_token'))) {
            $favorisRepository->remove($favori, true);
        }

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/articles/{id}', name: 'show_bien_by_categorie')]
    public function getBycategorie(BienRepository $bienRepository,Category $category,CategoryRepository $categoryRepository): Response
    {
        $id_bien = $category->getId();
        $categoryLibelle= $category->getLibelle();
        $biens = $bienRepository->findBy(
            ['category' => $id_bien]
        );
        
        return $this->render('category/show.html.twig', [
            'category' => $categoryLibelle,
            'biens' => $biens,
            'categories' => $categoryRepository->findAll()
        ]);
    }

}