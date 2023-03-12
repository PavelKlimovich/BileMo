<?php

namespace App\Controller;

use App\Entity\Product;
use App\Services\ProductService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/products')]
class ProductController extends AbstractController
{
    private ProductService $productService;
    private SerializerInterface $serializer;

    public function __construct(ProductService $productService, SerializerInterface $serializer) {
        $this->productService = $productService;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'product_list', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        $products = $this->productService->getProducts();

        if ($products) {
            $jsonProducts = $this->serializer->serialize($products, 'json');
            return new JsonResponse($jsonProducts, Response::HTTP_OK, ['accept' => 'json'], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'product_detail', methods: ['GET'])]
    public function getProductDetail(Product $product): JsonResponse 
    {
        if ($product) {
            $jsonProduct = $this->serializer->serialize($product, 'json');
            return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }
}
