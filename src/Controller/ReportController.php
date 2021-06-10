<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\DateIntervalFormType;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report/trips', name: 'trips_report', methods: ['GET', 'POST'])]
    public function tripsReport(Request $request, Pdf $knpSnappyPdf): Response
    {
        $form = $this->createForm(DateIntervalFormType::class);
        $form->handleRequest($request);
        $result = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $result = $this->getDoctrine()->getRepository(Ticket::class)->getTripsIncome($formData['dateStart'], $formData['dateEnd']);

            $html = $this->render('report/trip_report.html.twig', [
                'startDate' => $formData['dateStart'],
                'endDate' => $formData['dateEnd'],
                'result' => $result,
            ]);
            $knpSnappyPdf->setOption('encoding', 'utf-8');
            return new PdfResponse(
                $knpSnappyPdf->getOutputFromHtml($html),
                'Report.pdf',
            );
        }
        return $this->render('report/report_form.html.twig', [
            'title' => 'Отчет по рейсам',
            'result' => $result,
            'form' => $form->createView()
        ]);
    }


    #[Route('/report/cashiers', name: 'cashiers_report', methods: ['GET', 'POST'])]
    public function cashiersReport(Request $request,  Pdf $knpSnappyPdf): Response
    {
        $form = $this->createForm(DateIntervalFormType::class);
        $form->handleRequest($request);
        $result = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $result = $this->getDoctrine()->getRepository(Ticket::class)->getCashiersIncome($formData['dateStart'], $formData['dateEnd']);

            $html = $this->render('report/cashiers_report.html.twig', [
                'title' => 'Отчет по кассирам',
                'startDate' => $formData['dateStart'],
                'endDate' => $formData['dateEnd'],
                'result' => $result,
            ]);

            $knpSnappyPdf->setOption('encoding', 'utf-8');
            return new PdfResponse(
                $knpSnappyPdf->getOutputFromHtml($html),
                'Report.pdf',
            );
        }
        return $this->render('report/report_form.html.twig', [
            'title' => 'Отчет по кассирам',
            'result' => $result,
            'form' => $form->createView()
        ]);
    }

    #[Route('/report/routes', name: 'routes_report', methods: ['GET', 'POST'])]
    public function routesReport(Request $request,  Pdf $knpSnappyPdf): Response
    {
        $form = $this->createForm(DateIntervalFormType::class);
        $form->handleRequest($request);
        $result = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $result = $this->getDoctrine()->getRepository(Ticket::class)->getRoutesIncome($formData['dateStart'], $formData['dateEnd']);

            $html = $this->render('report/routes_report.html.twig', [
                'title' => 'Отчет по маршрутам',
                'startDate' => $formData['dateStart'],
                'endDate' => $formData['dateEnd'],
                'result' => $result,
            ]);

            $knpSnappyPdf->setOption('encoding', 'utf-8');
            return new PdfResponse(
                $knpSnappyPdf->getOutputFromHtml($html),
                'Report.pdf',
            );
        }
        return $this->render('report/report_form.html.twig', [
            'title' => 'Отчет по маршрутам',
            'result' => $result,
            'form' => $form->createView()
        ]);
    }
}
