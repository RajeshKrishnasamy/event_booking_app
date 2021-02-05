<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Login Form', $crawler->filter('h1')->text());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Event Booking App")')->count());

        $link = $crawler->filter('a:contains("Sign in")')->link();
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
        $this->assertEquals(1, $crawler->filter('a:contains("logout")')->count());

    }   
        
}
