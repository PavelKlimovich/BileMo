<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private $faker;

    public function __construct() {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 75; $i++) {
            $product = new Product();
            $product->setBrand($this->faker->company())
                ->setPrice($this->faker->randomFloat($nbMaxDecimals = 2,  $min = 10, $max = 1000))
                ->setName($this->faker->word())
                ->setDescription($this->faker->realText($maxNbChars = 200))
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            $manager->persist($product);
            $manager->flush();
        }
    }
}

