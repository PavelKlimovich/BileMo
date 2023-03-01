<?php

namespace App\Controller;

use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private UserService $userService;
    private SerializerInterface $serializer;

    public function __construct(UserService $userService, SerializerInterface $serializer) {
        $this->userService = $userService;
        $this->serializer = $serializer;
    }

    #[Route('/api/{id}/users', name: 'product_list')]
    public function index(): JsonResponse
    {
        $users = $this->userService->getUsers();

        if ($users) {
            $jsonProducts = $this->serializer->serialize($users, 'json');
            return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
