<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Article\Article;
use App\Domain\Auth\User;
use App\Domain\Commande\Entity\Commande;
use App\Domain\Delivery\Entity\Delivery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommandeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create('Fr');
        $k = 0;
        for ($i = 0; $i < 15; $i++) {
            $commande = new Commande();
            $commande->setUser($this->getReference(User::class . random_int(0,24)));
            for ($k = 0; $k < 5; $k++) {
                $commande->addArticle($this->getReference(Article::class . $i));
                $k++;
            }
            $commande->setDelivery($this->getReference(Delivery::class . random_int(0,9)));
            $manager->persist($commande);
            $this->addReference(Commande::class . $i,$commande);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
