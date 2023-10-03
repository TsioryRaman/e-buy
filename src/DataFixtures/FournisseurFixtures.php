<?php

namespace App\DataFixtures;

use App\Domain\Fournisseur\Entity\Fournisseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FournisseurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::Create('Fr');

        $k = 0;
        for($i = 0; $i < 10; $i++)
        {
            $fournisseur = new Fournisseur();
            $fournisseur->setName($faker->company);


            $manager->persist($fournisseur);
            $this->addReference(Fournisseur::class . $i,$fournisseur);
        }

        $manager->flush();
    }
}
