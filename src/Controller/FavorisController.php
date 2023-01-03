<?php

namespace App\Controller;
use App\Entity\Bien;
use App\Entity\Favoris;
use App\Form\FavorisType;;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BienRepository;
use Symfony\Component\Form\FormView;
use App\Repository\FavorisRepository;
use Doctrine\ORM\Mapping\Id;

use function PHPUnit\Framework\isNull;

class FavorisController extends AbstractController
{
    
    #[Route('/mon-panier', name: 'cart')]
    public function index(SessionInterface $session, BienRepository $bienRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
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
            compact("dataPanier", "total")
        );
    }

    #[Route('/add/{id}', name: 'add_to_cart', methods: ['GET', 'POST'])]
    public function add(Bien $bien, SessionInterface $session)
    {
        // On récupère le panier actuel
        $cpt=0;
        $favoris = $session->get("panier", []);
        $id = $bien->getId();
        if (empty($favoris[$id])) {
            $favoris[$id] = 1;
            $cpt +=1;
        } else {
            $favoris[$id] = 1;

        }
        $session->set("panier", $favoris,$cpt);
        return $this->redirectToRoute('app_home');
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Bien $bien, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $bien->getId();

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart");
    }

    #[Route('/delete', name: 'delete_all')]
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart");
    }

    #[Route('/adresse', name: 'adresse_envoi')]
    public function adresse(Request $resquest, SessionInterface $session, BienRepository $bienRepository, FavorisRepository $favorisRepository)
    {
        $favorList  = new Favoris();
        $form = $this-> createForm(FavorisType::class, $favorList);
        $form -> handleRequest($resquest);
        $panier = $session->get("panier", []);
        $dataPanier = [];
        $total = 0;
        return $this->render('favoris/adresse.html.twig', [
            'form'=>$form->createView(),
            ]);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($panier as $id) {
                $product = $bienRepository->find($id);
                $dataPanier[] = ["produit" => $product,];
                if (!isNull($product)) {
                    $id_prod = $product-> getId();
                    $Bienprix= $product-> getPrix();
                    $image = $product-> getImage();
                    $favorList->setBienprix($Bienprix);
                    $favorList->setIdBien($id_prod);
                    $favorList->setImage($image);
                    $favorisRepository->save($favorList, true);
                }
            }
        }
    }
}