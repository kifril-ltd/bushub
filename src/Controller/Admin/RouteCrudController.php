<?php

namespace App\Controller\Admin;

use App\Entity\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RouteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Route::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('departurePoint', 'Пункт отправления'),
            TextField::new('arrivalPoint', 'Пункт прибытия'),
            NumberField::new('length', 'Протяженность'),
            MoneyField::new('costPrice', 'Себестоимость')->setCurrency('RUB'),
            AssociationField::new('trips', 'Рейсы')->hideOnForm(),
            AssociationField::new('creator', 'Менеджер')->hideOnForm()
        ];
    }

}
