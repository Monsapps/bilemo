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
        for ($i = 0; $i < 50; $i++) {
            $product = new Product('Product ' .$i, 'Brand', 'Details for product ' . $i, new \DateTime() );
            $manager->persist($product);
        }

        $manager->flush();
    }
}