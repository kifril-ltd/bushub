<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/user/profile/{id}', name: 'user_profile')]
    public function index(User $user): Response
    {
        $tickets = $user->getPurchasedTickets();
        $routes = [];
        foreach ($tickets as $ticket){
            $routes[] = $ticket->getTrip()->getRoute();
        }
        return $this->render('user_profile/index.html.twig', [
            'tickets' => $tickets,
            'routes' => $routes,
        ]);
    }
}
