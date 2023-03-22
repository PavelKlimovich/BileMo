<?php

namespace App\Services;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService 
{
    private UserPasswordHasherInterface $passwordHasher; 
    private EntityManagerInterface $manager; 

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
    }

    public function addNewEntity(User $user, Customer $customer): array
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword)
            ->setRoles(['ROLE_USER'])
            ->setCustomer($customer)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $this->manager->persist($user);
        $this->manager>flush();
        dd($user);
        return [$user];
    }

    public function deleteEntity(User $user): bool
    {
        $this->manager->remove($user);
        $this->manager->flush();

        return true;
    }
}