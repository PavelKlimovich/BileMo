<?php

namespace App\Controller;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

#[Route('/products')]
class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private SerializerInterface $serializer;

    public function __construct(ProductRepository $productRepository, SerializerInterface $serializer)
    {
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
    }

    /**
     * Get a paginated list of products
     * 
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     description="Get a paginated list of products",
     *    
     * )
     * @OA\Parameter(
     *     name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default="1")
     * ),
     * @OA\Parameter(
     *     name="limit",
     *         in="query",
     *         description="Limit of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default="10")
     * ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=ProductDTO::class))
     *         )
     * ),
     * @OA\Response(
     *      response="404",
     *      description="Resource not found"
     * ),
     * @OA\Tag(name="Product")
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'product_list', methods: ['GET'])]
    public function getProducts(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $products = $this->productRepository->findAllWithPagination($page, $limit);
        $jsonProducts = $this->serializer->serialize(ProductDTO::init($products), 'json');

        return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
    }
    /**
     * Get product detail by ID
     * 
     * @OA\Get(
     *      path="/products/{id}",
     *      summary="Get product detail",
     *      description="Get product detail by ID",
     *      operationId="getProductDetail",
     *      tags={"Product"},
     * )
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the product",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Product detail",
     *          @OA\JsonContent(ref="#/components/schemas/ProductDTO")
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Product not found"
     *      )
     * 
     * @OA\Tag(name="Product")
     * 
     *  @param Product $product
     *  @return JsonResponse
     */
    #[Route('/{id}', name: 'product_detail', methods: ['GET'])]
    public function getProductDetail(Product $product): JsonResponse
    {
        $jsonProduct = $this->serializer->serialize(new ProductDTO($product), 'json');

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }
}
