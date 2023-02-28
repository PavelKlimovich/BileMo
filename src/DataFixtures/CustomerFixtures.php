<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CustomerFixtures extends Fixture
{
    private $faker;

    public function __construct() {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) {
            $customer = new Customer();
            $customer->setBrand($this->faker->company())
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            $manager->persist($customer);
            $manager->flush();

            $this->addReference('customer'.$i, $customer);
        }
    }
}
