<?php

namespace App\Tests\Controller;

use App\Entity\AppUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppUserControllerTest extends WebTestCase
{
    private $entityManager;

    protected function setUp(){

        parent::setUp();

        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager->beginTransaction();
        $this->entityManager->getConnection()->setAutoCommit(false);
    }

    protected function tearDown(){

        $this->entityManager->rollback();
        $this->entityManager->close();
        $this->entityManager = null;

    }

    public function testDeleteUser()
    {
        $appUser = $this->entityManager->getRepository(AppUser::class)->find(2);
        $this->entityManager->remove($appUser);
        $this->entityManager->flush();

        $this->assertNull($this->entityManager->getRepository(AppUser::class)->find(2));
        
    }
}
