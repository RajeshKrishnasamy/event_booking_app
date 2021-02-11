<?php

namespace App\Tests;

use App\Entity\AppUser;
use App\Security\TokenStorageDecorator;
use Doctrine\ORM\EntityManagerInterface;

trait SecurityTrait
{
    protected static $users = [];

    protected function login(string $role = 'ROLE_USER', bool $userFromDatabase = false): AppUser
    {
        $user = $this->getUser($role, $userFromDatabase);

        $tokenStorage = self::$container->get('security.token_storage');

        if ($tokenStorage instanceof TokenStorageDecorator) {
            $tokenStorage->setUser($user);
        } else {
            $tokenStorage->setToken(
                TokenStorageDecorator::getNewToken($user)
            );
        }

        return $tokenStorage->getToken()->getUser();
    }

    public function logout()
    {
        $tokenStorage = self::$container->get('security.token_storage');
        $tokenStorage->setToken(null);
    }

    protected function getUser(string $role = 'ROLE_USER', bool $userFromDatabase = false): AppUser
    {
        if (empty(self::$users[$role])) {
            self::$users[$role] = $userFromDatabase
                ? ($this->getFirstUserByRole($role) ?: $this->createNewUser($role, $userFromDatabase))
                : $this->createNewUser($role, $userFromDatabase);
        }

        return self::$users[$role];
    }

    protected function getFirstUserByRole(string $role = 'ROLE_USER'): ?AppUser
    {
        $entityManager = self::$container->get('doctrine.orm.default_entity_manager');

        return $entityManager->getRepository(AppUser::class)
            ->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter(':role', '%' . $role . '%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    protected function createNewUser(string $role = 'ROLE_USE', bool $persist = false): AppUser
    {
        $user = (new AppUser())
            ->setRoles([$role])
            ->setEmail(sprintf('test_%s@test.com', strtolower($role)))
            ->setPassword('test')
            ->setFirstName('Test')
            ->setLastName('Test');

        if ($persist) {
            $entityManager = self::$container->get('doctrine.orm.default_entity_manager');
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $user;
    }
}
