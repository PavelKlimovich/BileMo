<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class CustomerFixtures extends Fixture
{
    private $passwordHasher;
    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) {
            $customer = new Customer();
            $plaintextPassword = 'password';
            $hashedPassword = $this->passwordHasher->hashPassword($customer, $plaintextPassword);
            $customer->setBrand($this->faker->company())
                ->setEmail($this->faker->email())
                ->setPassword($hashedPassword)
                ->setRoles(['ROLE_CUSTOMER'])
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            $manager->persist($customer);
            $manager->flush();

            $this->addReference('customer'.$i, $customer);
        }
    }
}
