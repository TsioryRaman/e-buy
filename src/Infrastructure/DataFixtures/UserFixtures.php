<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Auth\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $encoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('FR');

        for($i = 0; $i<25;$i++)
        {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->hashPassword($user,"ebuy123"));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $this->addReference(User::class . $i,$user);

        }

        $manager->flush();
    }
}
