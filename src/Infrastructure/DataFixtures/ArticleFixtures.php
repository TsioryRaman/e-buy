<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Article\Article;
use App\Domain\Fournisseur\Entity\Fournisseur;
use Cocur\Slugify\Slugify;
use DateTime;
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
        for ($i = 0; $i < 1000; $i++) {
            $name = $faker->name;
            $article = (new Article())
                ->setName($name)
                ->setSlug((new Slugify())->slugify($name))
                ->setBrand($faker->userAgent)
                ->setDescription($faker->words(200, true))
                ->setPrice(random_int(100, 10000))
                ->setQuantity(random_int(1, 15))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setPostalCode($faker->postcode)
                ->setAddress($faker->address)
                ->setCategory($this->getReference(CategoryFixtures::class . random_int(0, 4)))
                ->setFournisseur($this->getReference(Fournisseur::class . random_int(0, 9)))
                ->setUpdatedAt(new \DateTimeImmutable('now'));

            $this->addReference(Article::class . $i, $article);
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
