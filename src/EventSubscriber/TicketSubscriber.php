<?php

namespace App\EventSubscriber;

use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TicketSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => 'setSaleDateTime',
        ];
    }

    public function setSaleDateTime($event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Ticket) {
            $entity->setSaleDatetime(new \DateTime);
        }
    }
}
