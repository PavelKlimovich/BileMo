<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    public function __construct() {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 25; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setStreet($this->faker->streetName())
                ->setStreetBis($this->faker->streetAddress())
                ->setCity($this->faker->city())
                ->setCountry($this->faker->country())
                ->setZipCode((string) $this->faker->postcode())
                ->setPhoneNumber($this->faker->phoneNumber())
                ->setCustomer($this->getReference('customer'.random_int(1, 9)))
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            $manager->persist($user);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            CustomerFixtures::class,
        );
    }
}

