<?php

namespace App\Controller\Professional;

use App\Entity\CleaningRequest;
use App\Entity\Professional;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/pro', routeName: 'pro')]
class ProfessionalDashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
         return $this->render('pro/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CleanGo');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToUrl('Retour au site', 'fa fa-home', '/');

        if ($this->isGranted('ROLE_PROFESSIONAL')) {
            yield MenuItem::section('Espace Professionnel');
            yield MenuItem::linkToCrud('Mes Informations', 'fas fa-user-edit', Professional::class);
        }

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        if ($this->isGranted('ROLE_PROFESSIONAL')) {
            yield MenuItem::section('Demandes de ménage');
            yield MenuItem::linkToCrud('Voir les demandes', 'fa fa-broom', CleaningRequest::class);
            // Ajout du lien vers les demandes acceptées
            yield MenuItem::linkToCrud(
                'Demandes acceptées',
                'fa fa-check',
                CleaningRequest::class
            )->setController(\App\Controller\Professional\AcceptedCleaningRequestCrudController::class);
        }
    }

}
