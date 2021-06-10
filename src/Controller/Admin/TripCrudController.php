<?php

namespace App\Controller\Admin;

use App\Entity\Bus;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;

class TripCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Trip::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $buses = $this->entityManager->getRepository(Bus::class)->findAllUnused(null);
        if ($pageName === Crud::PAGE_EDIT) {

            $context = $this->get(AdminContextProvider::class)->getContext();
            $trip = $this->entityManager->getRepository(Trip::class)->findOneBy(['id'=>$context->getEntity()->getPrimaryKeyValue()]);
            $buses = $this->entityManager->getRepository(Bus::class)->findAllUnused($context->getEntity()->getPrimaryKeyValue());
        }
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('route', 'Маршрут')->setRequired(true),
            TimeField::new('departureTime', 'Время отправления')->setFormat('H:mm'),
            TimeField::new('arrivalTime', 'Время прибытия')->setFormat('H:mm'),
            AssociationField::new('bus', 'Автобус')->setFormTypeOption(
                'choices', $buses
            ),
            ChoiceField::new('regularity', 'Регулярность')
                ->setChoices([
                    'Пн' => 'Пн',
                    'Вт' => 'Вт',
                    'Ср' => 'Ср',
                    'Чт' => 'Чт',
                    'Пт' => 'Пт',
                    'Сб' => 'Сб',
                    'Вск' =>'Вск',
                ])
                ->allowMultipleChoices(true),
        ];
    }
}
