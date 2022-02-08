<?php

namespace App\Service;

use App\Model\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductService
{

    private $productRepo;
    private $router;

    public function __construct(ProductRepository $productRepo, UrlGeneratorInterface $router)
    {
        $this->productRepo = $productRepo;
        $this->router = $router;
    }

    public function getProductList(ParamFetcherInterface $paramFetch): Product
    {

        $pagerFanta = $this->productRepo->search(
            $paramFetch->get('keyword'),
            $paramFetch->get('order'),
            $paramFetch->get('limit'),
            $paramFetch->get('page')
        );

        return new Product($pagerFanta, $this->router);
    }

}
