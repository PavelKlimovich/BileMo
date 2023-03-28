<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService 
{
    private UserPasswordHasherInterface $passwordHasher; 
    private EntityManagerInterface $entityManager; 

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public function addNewUser(User $user, Customer $customer, EntityManagerInterface $entityManager): array
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setRoles(['ROLE_USER'])
            ->setCustomer($customer)
            ->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return [$user];
    }

    public function deleteEntity(User $user): bool
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return true;
    }
}