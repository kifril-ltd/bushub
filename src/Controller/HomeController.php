<?php

namespace App\Controller;

use App\Entity\Route as RouteEntity;
use App\Repository\RouteRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(RouteRepository $routeRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'routes' => $routeRepository->findAll(),
        ]);
    }

    #[Route('/trips/{id}', name: 'trips')]
    public function trips4Route(Request $request, RouteEntity $route): Response
    {
        return $this->render('home/trips4route.html.twig', [
            'trips' => $route->getTrips(),
        ]);
    }
}
