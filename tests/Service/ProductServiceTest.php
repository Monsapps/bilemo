<?php

namespace App\Test\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductServiceTest extends KernelTestCase
{

    private $managerRegistry;
    private $productRepo;
    private $router;
    private $validator;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->managerRegistry = $container->get(ManagerRegistry::class);
        $this->productRepo = $container->get(ProductRepository::class);
        $this->router = $container->get(UrlGeneratorInterface::class);
        $this->validator = $container->get(ValidatorInterface::class);
    }

    public function testAddProductGoodData(): void
    {
        $productService = new ProductService($this->managerRegistry, $this->productRepo, $this->router, $this->validator);
        $arrayData = [
            "name" => "New product",
            "brand" => "New brand",
            "details" => "New details",
            "releaseDate" => "2022-02-03 09:51:10"
        ];

        $product = $productService->addProduct($arrayData);

        $productResult = new Product();
        $productResult->setName("New product");
        $productResult->setBrand("New brand");
        $productResult->setDetails('New details');
        $productResult->setReleaseDate(new \DateTime('2022-02-03 09:51:10'));

        $this->assertSame($productResult->getName(), $product->getName());
        $this->assertSame($productResult->getBrand(), $product->getBrand());
        $this->assertSame($productResult->getDetails(), $product->getDetails());
        $this->assertEquals($productResult->getReleaseDate(), $product->getReleaseDate());
    }

    public function testAddProductMissingData(): void
    {

        $this->expectException(ValidationFailedException::class);

        $productService = new ProductService($this->managerRegistry, $this->productRepo, $this->router, $this->validator);
        $arrayData = [
            "brand" => "New brand",
            "details" => "New details",
            "releaseDate" => "2022-02-03 09:51:10"
        ];

        $productService->addProduct($arrayData);
    }

    public function testEditProductGoodData(): void
    {
        $productResult = new Product();
        $productResult->setName("New product");
        $productResult->setBrand("New brand");
        $productResult->setDetails('New details');
        $productResult->setReleaseDate(new \DateTime('2022-02-03 09:51:10'));

        $productService = new ProductService($this->managerRegistry, $this->productRepo, $this->router, $this->validator);
        $arrayData = [
            "name" => "Old product"
        ];

        $product = $productService->editProduct($productResult, $arrayData);

        $productResult->setName('Old product');

        $this->assertSame($productResult->getName(), $product->getName());
        $this->assertSame($productResult->getBrand(), $product->getBrand());
        $this->assertSame($productResult->getDetails(), $product->getDetails());
        $this->assertEquals($productResult->getReleaseDate(), $product->getReleaseDate());
    }
    
}