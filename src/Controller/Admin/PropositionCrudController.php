<?php

namespace App\Controller\Admin;

use App\Entity\Proposition;
use App\Entity\Sale;
use App\Repository\SaleRepository;
use App\Service\NotificationService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class PropositionCrudController extends AbstractCrudController
{

    private $saleRepository;
    private $notificationService;
    private $crudUrlGenerator;

    public function __construct(SaleRepository $saleRepository, NotificationService $notificationService, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->saleRepository = $saleRepository;
        $this->notificationService = $notificationService;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Proposition::class;
    }

    public function validateProposition(AdminContext $adminContext)
    {
        /** @var Proposition $proposition */
        $proposition = $adminContext->getEntity()->getInstance();

        $sale = new Sale();
        $sale->setAcceptedAt(new \DateTimeImmutable());
        $sale->setProposition($proposition);
        $sale->setAmount($proposition->getAmountWithoutTaxes());
        $sale->setBonificationRate($proposition->getBonificationRate());
        $sale->setCommissionRate($proposition->getCommissionRate());

        $this->getDoctrine()->getManager()->persist($sale);
        $this->getDoctrine()->getManager()->flush();

        $this->notificationService->send($proposition);

        return $this->redirect($this
            ->crudUrlGenerator
            ->build()
            ->setController(SaleCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl()
        );
    }

    public function configureActions(Actions $actions): Actions
    {
        $validateProposition = Action::new('validate_proposition', 'Valider vente', 'fa fa-money')
            ->displayIf(function ($entity) {
                return ($this->saleRepository->findOneBy(['Proposition' => $entity]) !== null) ? false : true;
            })
            ->linkToCrudAction('validateProposition');


        return $actions
            ->add(Crud::PAGE_INDEX, $validateProposition);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('Vendor')
                ->setRequired(true)
                ->setPermission('ROLE_ADMIN'),
            TextField::new('RefCustomer')
                ->setLabel('Client'),
            DateField::new('CreatedAt')
                ->hideOnForm()
                ->hideOnDetail()
                ->setLabel('Date'),
            IntegerField::new('amount')
                ->setLabel('Montant TTC'),
            IntegerField::new('ShippingFees')
                ->setLabel('Port'),
            IntegerField::new('ShippingFeesDiscount')
                ->setLabel('Remise port'),
            IntegerField::new('VendorCost')
                ->setLabel('Coût vendeur'),
            IntegerField::new('VendorCostRate')
                ->setLabel('Tx coût vendeur')
                ->hideOnForm(),
            IntegerField::new('DiscountRate')
                ->setLabel('Tx remise'),
            NumberField::new('BonificationRate')
                ->setLabel('Bonification')
                ->hideOnForm(),
            NumberField::new('CommissionRate')
                ->setLabel('Commission')
                ->hideOnForm(),
            IntegerField::new('CommssionAmount')
                ->setLabel('Mt commission')
                ->hideOnForm(),
        ];
    }
}
