<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadUserFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('demo@email.com')
            ->setFirstname('Mario')
            ->setLastname('Rossi')
            ->setRoles(['ROLE_USER']);

        $password = $this->hasher->hashPassword($user, 'pass1234');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
