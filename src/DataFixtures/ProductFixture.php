<?php
/**
 * Add some product to database
 */
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 51; $i++) {
            $product = new Product();
            $product->setName('Product ' .$i);
            $product->setBrand('Brand');
            $product->setDetails('Details for product ' . $i);
            $product->setReleaseDate(new \DateTime());
            $manager->persist($product);
        }

        $manager->flush();
    }
}