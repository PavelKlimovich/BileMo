<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Entity\Customer;
use App\Services\UserService;
use App\Services\SecurityService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('customers')]
#[IsGranted('ROLE_CUSTOMER')]
class VisitorController extends AbstractController
{
    private SerializerInterface $serializer;
    private UserRepository $userRepository;
    private SecurityService $securityService;
    private UserService $userService;

    public function __construct(
        SerializerInterface $serializer,
        UserRepository $userRepository,
        SecurityService $securityService,
        UserService $userService
        ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->securityService = $securityService;
        $this->userService = $userService;
    }

    #[Route('/{id}/users', name: 'users_list', methods: ['GET'])]
    public function index(Customer $customer): JsonResponse
    {  
        if (!$customer) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        if (!$this->securityService->ifAuthorisation($customer)) {
            return new JsonResponse(['message' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $users = $this->userRepository->getUsersForCustomer($customer->getId());
        $usersDTO = UserDTO::init($users);
        $jsonProducts = $this->serializer->serialize($usersDTO, 'json');
        
        return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/{id}/users/{user}', name: 'user_detail', methods: ['GET'])]
    public function show(Customer $customer, User $user): JsonResponse
    {  
        if (!$customer || !$user) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        if (!$this->securityService->ifAuthorisation($customer)) {
            return new JsonResponse(['message' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->getUserForCustomer($customer->getId(), $user->getId());
        $usersDTO = UserDTO::init($user);
        $jsonProducts = $this->serializer->serialize($usersDTO, 'json');
        
        return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/{id}/users', name: 'user_store', methods: ['POST'])]
    public function store(Customer $customer, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {  
        if (!$customer) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        if (!$this->securityService->ifAuthorisation($customer)) {
            return new JsonResponse(['message' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
  
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = $validator->validate($user);

        if ($errors->count() > 0) {
            return new JsonResponse($this->serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $user = $this->userService->addNewUser($user, $customer, $entityManager);
        $userDTO = UserDTO::init($user);
        $jsonUser = $this->serializer->serialize($userDTO, 'json');
        
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ['accept' => 'json'], true);
    }

    #[Route('/{id}/users/{user}', name: 'user_delete', methods: ['DELETE'])]
    public function deleteBook(Customer $customer, User $user): JsonResponse 
    {
        if (!$customer || !$user) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        if (!$this->securityService->ifAuthorisation($customer)) {
            return new JsonResponse(['message' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $this->userService->deleteEntity($user);

        return new JsonResponse(['message' => '204 No content'], Response::HTTP_NO_CONTENT);
    }
}
