<?php

namespace App\Controller\Admin;

use App\Entity\Sale;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SaleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sale::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle('index', 'Ventes')
            ->setPageTitle('new', 'Nouveau')
            ->setPageTitle('edit', 'Editer')
            ->setPageTitle('detail', 'Consulter détails')
            ->setEntityLabelInPlural('Ventes')
            ->setEntityLabelInSingular('Vente');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $result = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->isGranted('ROLE_VENDOR')) {
            $result->join('entity.Proposition', 'p');
            $result->andWhere('p.Vendor = :vendor');
            $result->setParameter('vendor', $this->getUser());
        }

        $result->orderBy('entity.acceptedAt','DESC');

        return $result;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->disable(Action::NEW);

        if ($this->isGranted('ROLE_VENDOR')) {
            $actions->disable(Action::EDIT, Action::DELETE);
        }

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            DateField::new('acceptedAt')
                ->setLabel('Date'),
            TextField::new('vendor')
                ->setLabel('Vendeur')
                ->hideOnForm(),
            IntegerField::new('amount')
                ->setLabel('Montant HT'),
            IntegerField::new('amountTaxes')
                ->setLabel('Montant TTC'),
            NumberField::new('BonificationRate')
                ->setLabel('Bonification')
                ->setNumDecimals(2),
            NumberField::new('CommissionRate')
                ->setNumDecimals(2)
                ->setLabel('Commission (%)'),
            IntegerField::new('commission')
                ->setLabel('Commission')
                ->hideOnForm(),
        ];
    }
}
