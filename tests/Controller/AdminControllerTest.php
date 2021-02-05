<?php

namespace App\Tests\Manager;

use App\Entity\Event;
use App\Tests\SecurityTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdminControllerTest extends KernelTestCase
{
    use SecurityTrait;
    protected $manager;

    
    protected function setUp()
    {
        self::bootKernel();
        $this->manager = self::$container->get('doctrine.orm.default_entity_manager');
    }

    public function testCreateEvent()
    {
            $user = $this->login('ROLE_ADMIN', true);

            $dateTime = new \DateTime();
            $endTime = $dateTime->modify('+ 1 hour');

            $event = new Event();
            $event->setName("event test");
            $event->setDescription("event description test");
            $event->setSeats(rand(10,100));
            $event->setDate(new \DateTime());
            $event->setStartTime(new \DateTime());
            $event->setEndTime($endTime);
            $event->setAdmin($user);
            $event->setStatus(1);
            $this->manager->persist($event);
            $this->manager->flush();

        $this->assertIsInt($event->getId());
        $this->assertInstanceOf('App\Entity\AppUser', $event->getAdmin());

        $this->logout();
    }

}