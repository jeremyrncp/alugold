<?php

namespace App\Controller\Admin;

use App\Entity\Proposition;
use App\Entity\Sale;
use App\Entity\Vendor;
use App\Form\MonthlySalesType;
use App\Form\VendorSalesType;
use App\Repository\SaleRepository;
use App\Service\SaleService;
use App\VO\VendorSalesVO;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @var CrudUrlGenerator
     */
    private $crudUrlGenerator;

    /**
     * @var SaleRepository
     */
    private $saleRepository;

    /**
     * @var SaleService
     */
    private $saleService;

    /**
     * @var AdminContextProvider
     */
    private $adminContextProvider;

    /**
     * DashboardController constructor.
     * @param CrudUrlGenerator $crudUrlGenerator
     * @param SaleRepository $saleRepository
     * @param SaleService $saleService
     * @param AdminContextProvider $adminContextProvider
     */
    public function __construct(
        CrudUrlGenerator $crudUrlGenerator,
        SaleRepository $saleRepository,
        SaleService $saleService,
        AdminContextProvider $adminContextProvider
    ) {
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->saleRepository = $saleRepository;
        $this->saleService = $saleService;
        $this->adminContextProvider = $adminContextProvider;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        if ($this->isGranted('ROLE_VENDOR')) {
            return $this->salesAndGoalByVendor();
        }

        $monthAvailable = array_map(function ($elm) {
            return $elm['date'];
        }, $this->saleRepository->findAllMonthAvailable());

        $monthlySales = $this->createForm(MonthlySalesType::class, $monthAvailable);
        $monthlySales->handleRequest($this->adminContextProvider->getContext()->getRequest());

        if ($monthlySales->isSubmitted() && $monthlySales->isValid()) {
            $month = $monthlySales->getData()['monthAvailable'];
        } else {
            $month = current($monthAvailable);
        }

        $sales = $this->saleRepository->findByMonth(new \DateTimeImmutable('01-' . $month));

        return $this->render('dashboard_admin.html.twig', [
            'formMonthAvailable' => $monthlySales->createView(),
            'sales' => $this->saleService->groupByVendor($sales),
            'month' => $month
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/exportsales/{month}",  name="export_sales")
     */
    public function export($month)
    {
        $spreadsheet = $this->getSalesSpreedsheet($month);

        $writer = new Xlsx($spreadsheet);

        $fileName = 'export_vente_objectifs.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($tempFile);

        return $this->file($tempFile, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/salesbyvendor", name="sales_vendor")
     */
    public function salesAndGoalByVendor(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $vendorsAvailable = $this->getDoctrine()->getRepository(Vendor::class)->findAll();
            $vendorSales = $this->createForm(VendorSalesType::class, $vendorsAvailable);
            $vendorSales->handleRequest($this->adminContextProvider->getContext()->getRequest());

            if ($vendorSales->isSubmitted() && $vendorSales->isValid()) {
                $sales = $this->saleRepository->findByVendor($vendorSales->getData()['vendor']);
            } else {
                $sales = $this->saleRepository->findByVendor(current($vendorsAvailable));
            }

            return $this->render('dashboard_vendor.html.twig', [
                'formVendorsAvailable' => $vendorSales->createView(),
                'sales' => $this->saleService->groupByMonth($sales)
            ]);
        }

        return $this->render('dashboard_vendor.html.twig', [
            'sales' => $this->saleService->groupByMonth($this->saleRepository->findByVendor($this->getUser()))
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ALUGOLD')
            ->disableUrlSignatures();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Dashboard vendeur', 'fa fa-user', 'sales_vendor')
            ->setPermission('ROLE_ADMIN');
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

    /**
     * @param $month
     * @return Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function getSalesSpreedsheet($month): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Vente et objectifs');

        $sheet->getCell('A1')->setValue('Email vendeur');
        $sheet->getCell('B1')->setValue('Ventes validées (TTC)');
        $sheet->getCell('C1')->setValue('Commission totale (€)');
        $sheet->getCell('D1')->setValue('Taux objectif');
        $sheet->getCell('E1')->setValue('Montant objectif (€)');

        $sales = $this->saleService->groupByVendor($this->saleRepository->findByMonth(new \DateTimeImmutable('01-' . $month)));
        $data = array_map(function ($elm) {
            /** @var VendorSalesVO $elm */
            return $elm->getExport();
        }, $sales->toArray());

        $sheet->fromArray($data, null, 'A2', true);
        return $spreadsheet;
    }
}
