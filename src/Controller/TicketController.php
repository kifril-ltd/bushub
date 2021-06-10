<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\TicketSellByCashierFormType;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/ticket')]
class TicketController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/new/{id}', name: 'ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Trip $trip): Response
    {
        $error = '';

        $calendarSettings = $this->getRegularity4Calendar($trip);

        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tripDate = new \DateTime($request->get('ticket')['tripDate']);

            if (!$this->isSeatsAvailabel($trip, $tripDate)) {
                $error = 'К сожалению, на данном рейсе закончились свободные места. Выберете другой день';
                return $this->render('ticket/new.html.twig', [
                    'ticket' => $ticket,
                    'form' => $form->createView(),
                    'trip' => $trip,
                    'error' => $error,
                    'disabledWeekdays' => '[' . implode(',', $calendarSettings['disabledWeekdays']) . ']',
                    'daysOfWeekHighlighted' => '[' . implode(',', $calendarSettings['allowedWeekdays']) . ']',
                ]);
            }

            $ticket->setPrice($trip->getPrice());
            $ticket->setPassenger($this->getUser());
            $ticket->setSaleDatetime(new \DateTime());
            $ticket->setSeller($this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => 'robot@robot.ru']));
            $ticket->setTrip($trip);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
            'trip' => $trip,
            'error' => $error,
            'disabledWeekdays' => '[' . implode(',', $calendarSettings['disabledWeekdays']) . ']',
            'daysOfWeekHighlighted' => '[' . implode(',', $calendarSettings['allowedWeekdays']) . ']',
        ]);
    }

    #[Route('/sell/{id}', name: 'ticket_sell', methods: ['GET', 'POST'])]
    public function sellTicket(Request $request, Trip $trip) : Response
    {
        $error = '';
        $calendarSettings = $this->getRegularity4Calendar($trip);

        $ticket = new Ticket();
        $form = $this->createForm(TicketSellByCashierFormType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tripDate = new \DateTime($request->get('ticket_sell_by_cashier_form')['tripDate']);

            if (!$this->isSeatsAvailabel($trip, $tripDate)) {
                $error = 'К сожалению, на данном рейсе закончились свободные места. Выберете другой день';
                return $this->render('ticket/sell.html.twig', [
                    'ticket' => $ticket,
                    'form' => $form->createView(),
                    'trip' => $trip,
                    'error' => $error,
                    'disabledWeekdays' => '[' . implode(',', $calendarSettings['disabledWeekdays']) . ']',
                    'daysOfWeekHighlighted' => '[' . implode(',', $calendarSettings['allowedWeekdays']) . ']',
                ]);
            }

            $passengerInfo = $request->get('ticket_sell_by_cashier_form')['passenger'];
            $passenger = $this->getDoctrine()->getRepository(User::class)->findOneBy(['passportNumber' => $passengerInfo['passportNumber']]);
            if ($passenger == null) {
                $passenger = $this->createUserForPassenger($passengerInfo);
            }

            $ticket->setPassenger($passenger);

            $ticket->setPrice($trip->getPrice());

            $ticket->setSaleDatetime(new \DateTime());
            $ticket->setSeller($this->getUser());
            $ticket->setTrip($trip);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('ticket/sell.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
            'trip' => $trip,
            'req' => $request,
            'error' => $error,
            'disabledWeekdays' => '[' . implode(',', $calendarSettings['disabledWeekdays']) . ']',
            'daysOfWeekHighlighted' => '[' . implode(',', $calendarSettings['allowedWeekdays']) . ']',
        ]);
    }

    private function isSeatsAvailabel($trip, $tripDate)
    {
        $currentTicketNumber = count($this->getDoctrine()
            ->getRepository(Ticket::class)
            ->findBy(['trip' => $trip->getId(), 'tripDate' => $tripDate]));
        $seatsNumber = $trip->getBus()->getSeatsNumber();

        return $currentTicketNumber < $seatsNumber;
    }

    private function getRegularity4Calendar($trip)
    {
        $weekdays = [
            'Пн' => 1,
            'Вт' => 2,
            'Ср' => 3,
            'Чт' => 4,
            'Пт' => 5,
            'Сб' => 6,
            'Вск' => 0,
        ];

        $regularity = $trip->getRegularity();

        $allowedWeekdays = [];
        foreach ($regularity as $weekday) {
            $allowedWeekdays[] = $weekdays[$weekday];
        }
        $disabledWeekdays = array_diff(range(0, 6), $allowedWeekdays);

        return [
            'allowedWeekdays' => $allowedWeekdays,
            'disabledWeekdays' => $disabledWeekdays,
        ];
    }

    private function createUserForPassenger($passengerInfo) : User
    {
        $passenger = new User();
        $passenger->setEmail($passengerInfo['email']);
        $passenger->setLastName($passengerInfo['lastName']);
        $passenger->setFirstName($passengerInfo['firstName']);
        $passenger->setPatronymic($passengerInfo['patronymic']);
        $passenger->setPassportNumber($passengerInfo['passportNumber']);
        $passenger->setPhone($passengerInfo['phone']);
        $passenger->setPassword($this->passwordEncoder->encodePassword(
            $passenger,
            'P@ssw0rd123'
        ));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($passenger);
        $manager->flush();

        return $passenger;
    }

}
