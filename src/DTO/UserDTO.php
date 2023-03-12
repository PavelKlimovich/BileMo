<?php

namespace App\DTO;

class UserDTO
{   
    public $id;
    public $firstName;
    public $lastName;

    public function __construct(mixed $user) 
    {
        $this->id = $user->getId();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        
    }


    public static function init(array $users)
    {
        $response = [];

        foreach($users as $key => $user){
           $response[$key] = new UserDTO($user);
        }

        return $response;
    }
}