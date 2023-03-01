<?php

namespace App\Services;

use App\Repository\ProductRepository;

class ProductService
{
    private ProductRepository $productRepository; 

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function getProduct(int $id)
    {
        return $this->productRepository->find($id);
    }

    public function getProducts()
    {
        return $this->productRepository->findAll();
    }

}