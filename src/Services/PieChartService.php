<?php

namespace App\Services;

use App\Entity\EmployeeEvents;
use App\Entity\Event;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\ORM\EntityManagerInterface;



class PieChartService {

    private $entityManager;
    private $event;
    private $resultData = ['yes' => 0, 'no' => 0, 'maybe' => 0];
    private $pieChart;

    public function __construct(EntityManagerInterface $entityManager, Event $event)
    {
        $this->entityManager = $entityManager;
        $this->event = $event;
        $this->formatData();
        $this->pieChart = new PieChart();

    }

    public function getChartObj() {
       
        $this->pieChart->getData()->setArrayToDataTable(
            [
             ['Task', 'Hours per Day'],
             ['yes',     $this->resultData['yes']],
             ['no',      $this->resultData['no']],
             ['maybe',   $this->resultData['maybe']]
            ]
        );
        $this->pieChart->getOptions()->setTitle('Event entry report');
        $this->pieChart->getOptions()->setHeight(500);
        $this->pieChart->getOptions()->setWidth(700);
        $this->pieChart->getOptions()->setIs3D(true);
        $this->pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $this->pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $this->pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $this->pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->pieChart;
    }

    public function formatData() {
          $entryData = $this->entityManager->getRepository(EmployeeEvents::class)->findByEntryCount($this->event->getId());
          foreach($entryData as $entryRowData) {
            $this->resultData[$entryRowData['entry']] = $entryRowData['count'];
        }
    }

}