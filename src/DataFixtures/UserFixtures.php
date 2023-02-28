<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->faker = Factory::create();
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) {
            $user = new User();
            $plaintextPassword = 'password';
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setEmail($this->faker->email())
                ->setPassword($hashedPassword)
                ->setRoles(['ROLE_CUSTOMER'])
                ->setCustomer($this->getReference('customer'.random_int(1, 9)))
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            $manager->persist($user);
            $manager->flush();
        }

        for ($i=0; $i < 25; $i++) {
            $user = new User();
            $plaintextPassword = 'password';
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setPassword($hashedPassword)
                ->setRoles(['ROLE_USER'])
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

