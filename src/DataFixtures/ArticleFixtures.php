<?php

namespace App\DataFixtures;

use App\Domain\Article\Article;
use App\Domain\Fournisseur\Entity\Fournisseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 100; $i++) 
        {
            $article = new Article();
            $article->setTitle($faker->name);
            $article->setDescription($faker->words(10, true));
            $article->setPrice(random_int(100,10000));
            $article->setQuantity(random_int(1,15));
            $article->setPostalCode($faker->postcode);
            $article->setAddress($faker->address);
            $article->setCategory($this->getReference(CategoryFixtures::class . random_int(0,4)));
            $article->setFournisseur($this->getReference(Fournisseur::class . random_int(0,9)));

            $this->addReference(Article::class . $i,$article);
            $manager->persist($article);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            FournisseurFixtures::class
        ];
    }
}
