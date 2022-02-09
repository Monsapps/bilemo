<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    #[Rest\Get('/products', name: 'product_list')]
    #[Rest\QueryParam(name: 'keyword', requirements: '[a-zA-Z0-9]', nullable: true, description: 'The keyword search for.')]
    #[Rest\QueryParam(name: 'order', requirements: 'asc|desc', default: 'asc', description: 'Sort order (asc or desc)')]
    #[Rest\QueryParam(name: 'limit', requirements: '\d+', default: '15', description: 'Max articles per page.')]
    #[Rest\QueryParam(name: 'page', requirements: '\d+', default: '1', description: 'The page number.')]
    /**
     * @Rest\View)
     */
    public function productList(ProductService $productService, ParamFetcherInterface $paramFetch): View
    {

        $products = $productService->getProductList($paramFetch);

        return new View($products);

        /*return new Response(
            $serializer->serialize($products, 'json'),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );*/
    }

    /**
     * @Rest\Get("/products/{id}", name="product_details")
     * @Rest\View(serializerGroups={"Details"})
     */
    public function productDetails(Product $product, SerializerInterface $serializer): View
    {

        return new View($product);
        return new Response(
            $serializer->serialize($product, 'json'),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );

    }
}
