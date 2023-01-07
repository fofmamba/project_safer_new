<?php

namespace App\Controller\Admin;

use App\Entity\Bien;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
class BienCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bien::class;
    }

    public function configureActions(Actions $actions): Actions 
    {
        return $actions
            ->add('index', 'detail');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('numero'),
            TextField::new('titre'),
            TextField::new('descriptif'),
            MoneyField::new('prix')->setCurrency('EUR'),
            ImageField::new('image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/biens')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            AssociationField::new('category')->autocomplete(),
            BooleanField::new('status', 'afficher dans les top produit'),
            BooleanField::new('isInHome', 'afficher dans les top produit')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Bien')
            ->setEntityLabelInPlural('Biens')
        ;
    }
    
}
