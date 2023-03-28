<?php

namespace App\DTO;

use DateTime;

class UserDTO
{   
    public int $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public object $createdAt;

    public function __construct(mixed $user) 
    {
        $this->id = $user->getId();
        $this->firstname = $user->getFirstName();
        $this->email = $user->getEmail();
        $this->lastname = $user->getLastName();
        $this->createdAt = $user->getCreatedAt();
    }

    /**
     * Return UserDTO collection.
     *
     * @param array $users
     * @return array
     */
    public static function init(array $users): array
    {
        $response = [];

        foreach($users as $key => $user){
           $response[$key] = new self($user);
        }

        return $response;
    }
}