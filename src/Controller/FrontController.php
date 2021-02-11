<?php

namespace App\Controller;

use App\Entity\EmployeeEvents;
use App\Entity\Event;
use App\Utils\DateAndTimeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    use DateAndTimeTrait;

    /**
     * Display all the active Events
     * Users - ROLE_USER
     * @Route("/event_list", name="event_list")
     */
    public function eventList(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $entityManager = $this->getDoctrine()->getManager();
        $events = $entityManager->getRepository(Event::class)->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'page_title' => 'Event List',
        ]);
    }

    /**
     * View an Event details
     * Users - ROLE_USER
     * @Route("/event_view/{id}", name="event_view",methods={"GET"})
     */
    public function eventView(Event $event): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $duration = $this->durationCalculater($event->getStartTime(), $event->getEndTime());
        return $this->render('event/view.html.twig', [
            'event' => $event,
            'duration' => $duration,
            'page_title' => 'Event View',
        ]);
    }

    /**
     * Add an entry to an Event
     * Users - ROLE_USER
     * @Route("/event_entry/{id}/{option}", name="event_entry", methods={"GET"})
     */
    public function eventEntry($id, $option): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Event::class)->find($id);
        $currentUser = $this->getUser();
        $employeeEvent = $entityManager->getRepository(EmployeeEvents::class)->findOneBy(array('user_id' => $currentUser->getId(), 'event_id' => $event->getid()));
        if (!$employeeEvent) {
            $employeeEvent = new EmployeeEvents();
        }
        $employeeEvent->setEventId($event);
        $employeeEvent->setUserId($currentUser);
        $employeeEvent->setEntry($option);
        $entityManager->persist($employeeEvent);
        $entityManager->flush();
        return $this->redirectToRoute('event_list');

        return $this->render('event/view.html.twig', [
            'event' => $event,
            'page_title' => 'Event View',
        ]);
    }

}
