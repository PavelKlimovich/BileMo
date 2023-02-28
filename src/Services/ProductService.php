<?php

namespace App\Services;

use App\Repository\ProductRepository;

class ProductService
{
    private ProductRepository $productRepository; 

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function getProducts()
    {
        return $this->productRepository->findAll();
    }
}