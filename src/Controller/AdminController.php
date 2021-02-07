<?php

namespace App\Controller;

use App\Entity\AppUser;
use App\Entity\Event;
use App\Form\EventType;
use App\Services\PieChartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
         $this->security = $security;
    }

    /**
     * Create new event
     * @Route("/new_event", name="event_new")
     */
    public function newEvent(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $events = $entityManager->getRepository(Event::class)->findAll();
        $user = $this->security->getUser();
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $errors = $form->getErrors(true, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setName($form->get('name')->getData());
            $event->setDescription($form->get('description')->getData());
            $event->setSeats($form->get('seats')->getData());
            $event->setDate($form->get('date')->getData());
            $event->setStartTime($form->get('start_time')->getData());
            $event->setEndTime($form->get('end_time')->getData());
            $event->setAdmin($user);
            $event->setStatus(1);
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('event_list');
        }

        return $this->render('event/create.html.twig', [
            'page_title' => 'Create Event',
            'errors' => $errors,
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit Event
     * @Route("/event_edit/{id}", name="event_edit", methods={"GET"})
     */
    public function editEvent(Request $request, Event $event): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $event);
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(AppUser::class)->find(1);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);        

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setName($form->get('name')->getData());
            $event->setDescription($form->get('description')->getData());
            $event->setSeats($form->get('seats')->getData());
            $event->setDate($form->get('date')->getData());
            $event->setStartTime($form->get('start_time')->getData());
            $event->setEndTime($form->get('end_time')->getData());
            $event->setAdmin($user);
            $event->setStatus(1);
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('event_list');
        }

        return $this->render('event/edit.html.twig', [
            'page_title' => 'Edit Event',
            'form' => $form->createView()
        ]);
    }

    /**
     * Cancel event
     * @Route("/cancel_event/{id}", name="event_cancel", methods={"GET"})
     */
    public function cancelEvent(Request $request, Event $event): Response
    {
        $this->denyAccessUnlessGranted('CANCEL', $event);
        $entityManager = $this->getDoctrine()->getManager();
       if (!$event) {
            throw $this->createNotFoundException(
                'There are no such event'
            );
        }
        $event->setStatus(0);
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->redirectToRoute('event_list');
    }

    /**
     * Cancel event
     * @Route("/view_chart/{id}", name="event_chart", methods={"GET"})
     */
    public function viewChart(Event $event): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $pieChartService = new PieChartService($entityManager, $event);
        $chartData = false;
        if (count($event->getEmployeeEvents()) > 0) {
            $chartData = true;
        }
        $pieChart = $pieChartService->getChartObj();
        return $this->render('chart/chart.html.twig',
         [
             'piechart' => $pieChart,
             'event' => $event,
             'chartData' => $chartData
             ]
        );
    }
}
