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

    public function __construct(ProductRepository $productRepository, SerializerInterface $serializer) {
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
    }

    /**
     * Cette méthode permet de récupérer l'ensemble des produits.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des produits",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product   ::class, groups={"getProducts"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre d'éléments que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
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
        $productsDTO = ProductDTO::init($products);
        $jsonProducts = $this->serializer->serialize($productsDTO, 'json');

        return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);

    }

    #[Route('/{id}', name: 'product_detail', methods: ['GET'])]
    public function getProductDetail(Product $product): JsonResponse 
    {
        $productsDTO = new ProductDTO($product);
        $jsonProduct = $this->serializer->serialize($productsDTO, 'json');

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
   }
}
