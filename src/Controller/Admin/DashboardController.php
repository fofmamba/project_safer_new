<?php

namespace App\Controller\Admin;

use App\Entity\Bien;
use App\Entity\Category;
use App\Entity\Favoris;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    )
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(BienCrudController::class)->generateUrl());

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Safer Project');
    }

    public function configureMenuItems(): iterable
    {

    yield MenuItem::linkToDashboard('Tableau de Bord', 'fa fa-home');

    yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);

    yield MenuItem::linkToCrud('Bien', 'fas fa-tag', Bien::class);

    yield MenuItem::linkToCrud('favoris', 'fas fa-heart', Favoris::class);
    
    yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', Utilisateur::class);

    // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }   
}
