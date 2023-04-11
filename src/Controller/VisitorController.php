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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

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

    /**
     * Get a list of users for a customer
     * 
     * @OA\Get(
     *     path="/{id}/users",
     *     summary="Get a list of users for a customer",
     *     tags={"Users"},
     * ),
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the customer",
     *     @OA\Schema(
     *         type="integer",
     *         format="int64",
     *         minimum=1
     *     )
     * ),
     * @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(
     *          type="integer",
     *          minimum=1
     *      )
     * ),
     * @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      description="Number of items per page",
     *      @OA\Schema(
     *          type="integer",
     *          minimum=1,
     *          maximum=50,
     *          default=10
     *     )
     * ),
     * @OA\Response(
     *      response="200",
     *      description="List of users",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=UserDTO::class))
     *      )
     * ),
     * @OA\Response(
     *      response="401",
     *      description="Unauthorized",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *              example="401 Unauthorized"
     *          )
     *      )
     * ),
     * @OA\Response(
     *      response="404",
     *      description="Not Found"
     * ),
     * 
     * @OA\Tag(name="Users")
     * 
     * 
     * @param Customer $customer
     * @param Request $request
     * @return JsonResponse
     * 
     */
    #[Route('/{id}/users', name: 'users_list', methods: ['GET'])]
    public function index(Customer $customer, Request $request): JsonResponse
    {
        if (!$this->securityService->ifAuthorisation($customer)) {
            return new JsonResponse(['message' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $users = $this->userRepository->getUsersForCustomer($customer->getId(), $page, $limit);
        $usersDTO = UserDTO::init($users);
        $jsonProducts = $this->serializer->serialize($usersDTO, 'json');

        return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
    }


    /**
     * Get details of a user for a specific customer
     * 
     * @OA\Get(
     *     path="/{id}/users/{user}",
     *     summary="Get user details",
     *     description="Get details of a user for a specific customer",
     * ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the customer",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=UserDTO::class))
     *      )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer or user not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * @OA\Tag(name="Users")
     * 
     * @param Customer $customer
     * @param User $user
     * @return JsonResponse
     * 
     */
    #[Route('/{id}/users/{user}', name: 'user_detail', methods: ['GET'])]
    public function show(Customer $customer, User $user): JsonResponse
    {
        if (!$this->securityService->ifAuthorisation($customer)) {
            return new JsonResponse(['message' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->getUserForCustomer($customer->getId(), $user->getId());
        $usersDTO = UserDTO::init($user);
        $jsonProducts = $this->serializer->serialize($usersDTO, 'json');

        return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /**
     * Add a new user for a customer
     * 
     * @OA\Post(
     *     path="/{id}/users",
     *     summary="Add a new user for a customer",
     *     tags={"User"},
     * ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="User object that needs to be added",
     *         required=true,
     *         @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=UserDTO::class))
     *      )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User added successfully",
     *         @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=UserDTO::class))
     *      )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid input data",
     *         @OA\JsonContent(ref=@Model(type=ErrorResponse::class))
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(ref=@Model(type=ErrorResponse::class))
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Customer not found",
     *         @OA\JsonContent(ref=@Model(type=ErrorResponse::class))
     *     )
     * )
     * @OA\Tag(name="Users")
     * 
     *  @param Customer $customer
     *  @param Request $request
     *  @param EntityManagerInterface $entityManager
     *  @param ValidatorInterface $validator
     *  @return JsonResponse
     * 
     */
    #[Route('/{id}/users', name: 'user_store', methods: ['POST'])]
    public function store(Customer $customer, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
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

    /**
     * Delete a user for a customer
     * 
     * @OA\Delete(
     *     path="/{id}/users/{user}",
     *     summary="Delete a user",
     * )
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the customer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="401 Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * 
     * @OA\Tag(name="Users")
     * @Security(name="Bearer")
     * 
     * 
     *  @param Customer $customer
     *  @param User $user
     *  @return JsonResponse
     */
    #[Route('/{id}/users/{user}', name: 'user_delete', methods: ['DELETE'])]
    public function deleteBook(Customer $customer, User $user): JsonResponse
    {
        if (!$this->securityService->ifAuthorisation($customer)) {
            return new JsonResponse(['message' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $this->userService->deleteEntity($user);

        return new JsonResponse(['message' => '204 No content'], Response::HTTP_NO_CONTENT);
    }
}
