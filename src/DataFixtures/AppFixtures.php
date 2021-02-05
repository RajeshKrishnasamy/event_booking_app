<?php

namespace App\DataFixtures;

use App\Entity\AppUser;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadEvents($manager);
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new AppUser();
            $firstName = 'first name '.$i;
            $lastName = 'last name '.$i;
            $email = 'user'.$i.'@test.com';
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    "password".$i
                )
            );
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        $manager->flush();
    }
    private function loadEvents(ObjectManager $manager) {

        // Create super user to create events
        $user = new AppUser();
        $firstName = 'super';
        $lastName = 'user';
        $email = 'admin@admin.com';
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                "admin"
            )
        );
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $dateTime = new \DateTime();
            $endTime = $dateTime->modify('+ 1 hour');

            $event = new Event();
            $event->setName("event ".$i);
            $event->setDescription("event description".$i);
            $event->setSeats(rand(10,100));
            $event->setDate(new \DateTime());
            $event->setStartTime(new \DateTime());
            $event->setEndTime($endTime);
            $event->setAdmin($user);
            $event->setStatus(1);
            $manager->persist($event);
        }

        $manager->flush();
    }
}