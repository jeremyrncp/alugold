<?php

namespace App\Controller\Admin;

use App\Entity\Proposition;
use App\Entity\Sale;
use App\Entity\Vendor;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @var CrudUrlGenerator
     */
    private $crudUrlGenerator;

    /**
     * DashboardController constructor.
     * @param CrudUrlGenerator $crudUrlGenerator
     */
    public function __construct(CrudUrlGenerator $crudUrlGenerator)
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ALUGOLD');
    }

    public function configureMenuItems(): iterable
    {
        /** @todo gérer permissions */
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Vendeurs', 'fas fa-industry', Vendor::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToUrl('Ajouter proposition', 'fa fa-paper-plane',
            $this
                ->crudUrlGenerator
                ->build()
                ->setController(PropositionCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );
        yield MenuItem::linkToCrud('Propositions', 'fa fa-paper-plane-o', Proposition::class);
        yield MenuItem::linkToCrud('Ventes', 'fa fa-money', Sale::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }
}
