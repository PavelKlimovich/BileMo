<?php

namespace App\DTO;

class ProductDTO
{   
    public int $id;
    public string $brand;
    public string $name;
    public float $price;
    public string $description;
    public object $createdAt;

    public function __construct(mixed $product) 
    {
        $this->id = $product->getId();
        $this->brand = $product->getBrand();
        $this->name = $product->getName();
        $this->price = $product->getPrice();
        $this->description = $product->getDescription();
        $this->createdAt = $product->getCreatedAt();
    }

    /**
     * Return ProductDTO collection.
     *
     * @param array $products
     * @return array
     */
    public static function init(array $products): array
    {
        $response = [];

        foreach($products as $key => $product){
           $response[$key] = new self($product);
        }

        return $response;
    }
}