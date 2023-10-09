<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Article\Article;
use App\Domain\Store\Entity\Store;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class StoreFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create('fr_FR');

        $k = 0;

        for ($i = 0; $i < 10; $i++) {
            $store = (new Store())
                ->setName($faker->company)
                ->setAddress($faker->address);
            for ($j = 0; $j < 5; $j++) {
                $store->addArticle($this->getReference(Article::class . $k));
                $k++;
            }
            $manager->persist($store);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class
        ];
    }
}
