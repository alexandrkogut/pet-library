<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    protected const ADMIN_EMAIL = 'admin@admin.com';
    protected const ADMIN_PASSWORD = 'password';

    public function __construct(
        protected UserPasswordHasherInterface $passwordHasher,
    )
    {}

    public function load(ObjectManager $manager): void
    {
        $user = $this->createUser(self::ADMIN_EMAIL, self::ADMIN_PASSWORD, [User::ROLE_ADMIN]);

        $manager->persist($user);
        $manager->flush();
    }

    protected function createUser(string $email, string $password, array $roles = []): User
    {
        $user = (new User())
            ->setEmail($email)
            ->setRoles($roles);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

        $user->setPassword($hashedPassword);

        return $user;
    }
}
