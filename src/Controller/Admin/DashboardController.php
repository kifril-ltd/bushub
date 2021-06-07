<?php

namespace App\Controller\Admin;

use App\Entity\Bus;
use App\Entity\Route;
use App\Entity\Ticket;
use App\Entity\Trip;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\LogoutMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('АИС "Автовокзал"');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('АИС "Автовокзал"', 'fa fa-home');
        yield MenuItem::linkToCrud('Автобусы', 'fas fa-list', Bus::class);
        yield MenuItem::linkToCrud('Маршруты', 'fas fa-list', Route::class);
        yield MenuItem::linkToCrud('Рейсы', 'fas fa-list', Trip::class);
        yield MenuItem::linkToCrud('Билеты', 'fas fa-list', Ticket::class);
        yield MenuItem::linkToCrud('Пользователи', 'fas fa-list', User::class);
        yield MenuItem::linkToRoute('Выйти из админ-панели', 'fa fa-sign-out', 'home');
    }
}
