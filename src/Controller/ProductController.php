<?php

namespace App\Controller;

use App\DTO\ProductDTO;
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
