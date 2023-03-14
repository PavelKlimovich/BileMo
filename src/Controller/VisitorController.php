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

#[Route('customers')]
//#[IsGranted('ROLE_CUSTOMER')]
class VisitorController extends AbstractController
{
    private SerializerInterface $serializer;
    private UserRepository $userRepository;

    public function __construct(
        SerializerInterface $serializer,
        UserRepository $userRepository) {

        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    #[Route('/{id}/users', name: 'users_list')]
    public function index(Customer $customer): JsonResponse
    {  
        if (!$customer) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $users = $this->userRepository->getUsersForCustomer($customer->getId());
        $usersDTO = UserDTO::init($users);
        $jsonProducts = $this->serializer->serialize($usersDTO, 'json');
        
        return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/{id}/users/{user}', name: 'user_detail')]
    public function show(Customer $customer, User $user): JsonResponse
    {  
        if (!$customer || !$user) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $user = $this->userRepository->getUserForCustomer($customer->getId(), $user->getId());
        $usersDTO = UserDTO::init($user);
        $jsonProducts = $this->serializer->serialize($usersDTO, 'json');
        
        return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
