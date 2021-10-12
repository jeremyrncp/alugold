<?php

namespace App\Controller\Admin;

use App\Entity\Vendor;
use App\Form\DiscountType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VendorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vendor::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle('index', 'Mes vendeurs')
            ->setPageTitle('new','Nouveau')
            ->setPageTitle('edit', 'Editer')
            ->setPageTitle('detail', 'Consulter détails')
            ->setEntityLabelInPlural('Vendeurs')
            ->setEntityLabelInSingular('Vendeur')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            EmailField::new('email')
                ->setLabel('Adresse email'),
            TextField::new('password')
                ->setLabel('Mot de passe')
                ->hideOnIndex()
                ->hideOnDetail(),
            TextField::new('name')
                ->setLabel('Nom'),
            TextField::new('firstname')
                ->setLabel('Prénom'),
            TextField::new('department')
                ->setLabel('Département'),
            IntegerField::new('goal')
                ->setLabel('Objectif (HT)'),
            IntegerField::new('commission')
                ->setLabel('Commission base (%)'),
            CollectionField::new('discounts')
                ->setLabel('Remises')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(DiscountType::class),
        ];
    }

}
