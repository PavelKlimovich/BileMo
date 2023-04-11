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
    public array $links;

    public function __construct(mixed $user)
    {
        $this->id = $user->getId();
        $this->firstname = $user->getFirstName();
        $this->email = $user->getEmail();
        $this->lastname = $user->getLastName();
        $this->createdAt = $user->getCreatedAt();
        $this->links = [
            'self' => [
                "href" =>  '/api/customers/' . $user->getCustomer()->getId() . '/users/' . $this->id
            ],
            'update' => [
                "href" =>  '/api/customers/' . $user->getCustomer()->getId() . '/users/' . $this->id
            ],
            'delete' => [
                "href" =>  '/api/customers/' . $user->getCustomer()->getId() . '/users/' . $this->id
            ]
        ];
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

        foreach ($users as $key => $user) {
            $response[$key] = new self($user);
        }

        return $response;
    }
}
