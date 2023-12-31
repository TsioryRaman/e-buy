<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Delivery\Entity\Delivery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class DeliveryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $faker = Faker\Factory::create('FR');

        for($i = 0; $i<10;$i++)
        {
            $delivery = (new Delivery())
                            ->setName($faker->name);
            $manager->persist($delivery);

            $this->addReference(Delivery::class . $i,$delivery);
        }

        $manager->flush();
    }
}
