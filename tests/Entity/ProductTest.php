<?php

namespace App\Test\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    function testSetGetterName(): void
    {
        $product = new Product();

        $product->setName('Product');

        $this->assertEquals('Product', $product->getName());
    }

    function testSetGetterBrand(): void
    {
        $product = new Product();
        
        $product->setBrand('Brand');

        $this->assertEquals('Brand', $product->getBrand());
    }

    function testSetGetterDetails(): void
    {
        $product = new Product();
        
        $product->setDetails('Details');

        $this->assertEquals('Details', $product->getDetails());
    }

    function testSetGetterReleaseDate(): void
    {
        $product = new Product();

        $datenow = new \DateTime();
        
        $product->setReleaseDate($datenow );

        $this->assertEquals($datenow , $product->getReleaseDate());
    }
}
