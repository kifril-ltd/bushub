<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class TicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id','Номер билета')->onlyOnDetail(),
            AssociationField::new('trip', 'Рейс'),
            MoneyField::new('price', 'Цена')->setCurrency('RUB'),
            AssociationField::new('passenger', 'Пассажир')->setRequired(true),
            AssociationField::new('seller', 'Кассир')->setRequired(true),
            DateField::new('tripDate', 'Дата поездки')->setFormat('dd.MM.yyyy'),
            DateTimeField::new('saleDatetime', 'Дата и время покупки')->onlyOnDetail()
        ];
    }
}
