<?php

namespace App\Services;

use App\Repository\UserRepository;

class UserService 
{
    private UserRepository $userRepository; 

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }


}