<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class NavigationController extends AbstractController
{
    #[Route('/navigation', name: 'app_navigation')]
    public function index(): Response
    {
        return $this->render('navigation/index.html.twig', [
            'controller_name' => 'NavigationController',
        ]);
    }
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    )
    {
    }
    #[Route('/admin', name: 'admin')]
    public function admin(SessionInterface $session)
    {
            //récupération de l'utilisateur security>Bundle
            $utilisateur = $this->getUser();
            $url = $this->adminUrlGenerator;
            //vérification des droits.
            if($utilisateur && in_array('ROLE_ADMIN', $utilisateur->getRoles())){
                return $this->redirect($url);
            }

            //redirection
            $session->set("message", "Vous n'avez pas le droit d'acceder à la page admin vous avez été redirigé sur cette page");
            return $this->redirectToRoute('home');

    }
}
