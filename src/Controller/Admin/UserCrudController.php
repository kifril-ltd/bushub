<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email', 'Email'),
            TelephoneField::new('phone', 'Номер телефона'),
            TextField::new('lastName', 'Фамилия'),
            TextField::new('firstName', 'Имя'),
            TextField::new('patronymic', 'Отчество'),
            TextField::new('passportNumber', 'Номер паспорта'),
            ChoiceField::new('roles', 'Категория пользователя')->setChoices([
                'Пассажир' => 'ROLE_USER',
                'Кассир' => 'ROLE_CASHIER',
                'Администратор' => 'ROLE_ADMIN',
            ])->allowMultipleChoices(true),
        ];
    }
}
