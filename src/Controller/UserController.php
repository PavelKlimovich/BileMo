<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Services\UserService;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private UserService $userService;
    private SerializerInterface $serializer;
    private CustomerRepository $customerRepository;

    public function __construct(UserService $userService, SerializerInterface $serializer, CustomerRepository $customerRepository) {
        $this->userService = $userService;
        $this->customerRepository = $customerRepository;
        $this->serializer = $serializer;
    }

    #[Route('/customers/{id}/users', name: 'product_list')]
    public function index(int $id): JsonResponse
    {   
        $customer = $this->userService->ifAuthorisation($id);

        if (!$customer) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        $customer = $this->customerRepository->find($customer);
        $users = $customer->getUsers();
        dd($customer->getUsers());
        if ($users) {
            $jsonProducts = $this->serializer->serialize($users, 'json');
            return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
