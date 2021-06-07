<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userCashier = new User();
        $userCustomer = new User();

        $userAdmin->setEmail('admin@admin.ru');
        $userAdmin->setLastName('Иванов');
        $userAdmin->setFirstName('Иван');
        $userAdmin->setPatronymic('Иванович');
        $userAdmin->setPassportNumber('1234567890');
        $userAdmin->setPassword($this->passwordEncoder->encodePassword(
            $userAdmin,
            'P@ssw0rd123'
        ));
        $userAdmin->setRoles(['ROLE_ADMIN']);

        $userCashier->setEmail('cashier@cashier.ru');
        $userCashier->setLastName('Сидорова');
        $userCashier->setFirstName('Мария');
        $userCashier->setPatronymic('Петровна');
        $userCashier->setPassportNumber('2344673291');
        $userCashier->setPassword($this->passwordEncoder->encodePassword(
            $userCashier,
            'P@ssw0rd123'
        ));
        $userCashier->setRoles(['ROLE_CASHIER']);

        $userCustomer->setEmail('user@user.ru');
        $userCustomer->setLastName('Сухоруков');
        $userCustomer->setFirstName('Кирилл');
        $userCustomer->setPatronymic('Олегович');
        $userCustomer->setPassportNumber('4219230899');
        $userCustomer->setPassword($this->passwordEncoder->encodePassword(
            $userCustomer,
            'P@ssw0rd123'
        ));

        $manager->persist($userAdmin);
        $manager->persist($userCashier);
        $manager->persist($userCustomer);

        $manager->flush();
    }
}
