<?php

namespace App\Services;

use App\Repository\UserRepository;

class UserService 
{
    private UserRepository $userRepository; 

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function ifAuthorisation(int $id)
    {
        $user = $this->userRepository->find($id);

        if ($user->getRoles()[0] == 'ROLE_CUSTOMER' && $user->getId() == $id) {
           return $user->getCustomer();
        }

        return null;
    }
}