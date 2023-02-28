<?php

namespace App\Controller;

use App\Services\ProductService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private ProductService $productService;
    private SerializerInterface $serializer;

    public function __construct(ProductService $productService, SerializerInterface $serializer) {
        $this->productService = $productService;
        $this->serializer = $serializer;
    }

    #[Route('/products', name: 'product_list')]
    public function index(): JsonResponse
    {
        $products = $this->productService->getProducts();
        $json = $this->serializer->serialize($products, 'json');
        
        return new JsonResponse($json, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
