<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Entity\Customer;
use App\Services\UserService;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SecurityController extends AbstractController
{
    private SerializerInterface $serializer;
    private UserRepository $userRepository;

    public function __construct(
        SerializerInterface $serializer,
        UserRepository $userRepository) {

        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    #[Route('/login', name: 'api_login')]
    public function login(): JsonResponse
    {
        return $this->json(['message' => 'success'], Response::HTTP_OK);
    }
}
