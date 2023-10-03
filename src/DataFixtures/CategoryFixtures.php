<?php

namespace App\DataFixtures;

use App\Domain\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create('fr_FR');

        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->name);
            $category->setDescription($faker->words(10, true));

            $manager->persist($category);
            $this->addReference(CategoryFixtures::class . $i, $category);
        }

        $manager->flush();
    }
}
