<?php

namespace App\Tests;

use App\Entity\Event;
use App\Entity\AppUser;
use App\Entity\EmployeeEvents;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase
{
    protected function setUp()
    {
        self::bootKernel();
        $this->manager = self::$container->get('doctrine.orm.default_entity_manager');
    }

    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Login', $crawler->filter('h3')->text());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Event Booking App")')->count());

        $link = $crawler->filter('a:contains("Sign Up")')->link();
        $crawler = $client->click($link);
        $this->assertContains('Repeat password', $client->getResponse()->getContent());
        
    }
   
    /**
    * @dataProvider provideUrls
    */
    public function testUrls($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());

        
     }

     public function provideUrls(){
         return [
             ['/login'],
             ['/user/new']
         ];
     }

    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'admin@admin.com';
        $form['password'] = 'admin';

        $crawler = $client->submit($form);
        $crawler =$client->followRedirect();
        $this->assertEquals(1, $crawler->filter('a:contains("Logout")')->count());

    }


    public function testCreateEmployeeEvent(){

        $user = $this->testGetFirstUserByRole();
        $event = $this->testGetFirstEvent();
        $employeeEvent = new EmployeeEvents();        
        $employeeEvent->setEventId($event);
        $employeeEvent->setUserId($user);
        $employeeEvent->setEntry('yes');
        $this->manager->persist($employeeEvent);
        $this->manager->flush();
        $this->assertInstanceOf('App\Entity\EmployeeEvents', $employeeEvent);
        $this->manager->remove($employeeEvent);
        $this->manager->flush();       

    }
    
    public function testGetFirstEvent(string $name = 'test'): ?Event
    {
        $event =  $this->manager->getRepository(Event::class)
            ->createQueryBuilder('e')
            ->where('e.name LIKE :name')
            ->setParameter(':name', '%'.$name.'%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        
        $this->assertInstanceOf('App\Entity\Event', $event);

        return $event;
    }


    public function testGetFirstUserByRole(string $role = 'ROLE_USER'): ?AppUser
    {      
        $user = $this->manager->getRepository(AppUser::class)
            ->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter(':role', '%'.$role.'%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        
        $this->assertInstanceOf('App\Entity\AppUser', $user);
        return $user;
    }

        
}
