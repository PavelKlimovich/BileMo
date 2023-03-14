<?php

namespace App\DTO;

class UserDTO
{   
    public int $id;
    public string $firstName;
    public string $lastName;
    public object $createdAt;

    public function __construct(mixed $user) 
    {
        $this->id = $user->getId();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
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