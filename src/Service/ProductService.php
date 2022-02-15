<?php

namespace App\Service;

use App\Entity\Product;
use App\Model\Product as ModelProduct;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService extends BaseService
{

    private $managerRegistry;
    private $productRepo;
    private $router;

    public function __construct(
        ManagerRegistry $managerRegistry,
        ProductRepository $productRepo,
        UrlGeneratorInterface $router,
        ValidatorInterface $validator)
    {
        $this->managerRegistry = $managerRegistry;
        $this->productRepo = $productRepo;
        $this->router = $router;

        parent::__construct($validator);
    }

    public function getProductList(ParamFetcherInterface $paramFetch): ModelProduct
    {

        $pagerFanta = $this->productRepo->search(
            $paramFetch->get('keyword'),
            $paramFetch->get('order'),
            $paramFetch->get('limit'),
            $paramFetch->get('page')
        );

        return new ModelProduct($pagerFanta, $this->router);
    }

    public function addProduct(array $data): Product
    {
        
        $product = new Product();
        
        $this->addProductInfo($product, $data);

        $this->entityValidator($product);

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }

    public function editProduct(Product $product, array $data): Product
    {

        $this->addProductInfo($product, $data);

        $this->entityValidator($product);

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->flush();

        return $product;
    }

    public function deleteProduct(Product $product): void
    {
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
    }

    private function addProductInfo(Product $product, array $data): Product
    {

        (!empty($data['name'])) ? $product->setName($data['name']) : '';
        (!empty($data['brand'])) ? $product->setBrand($data['brand']) : '';
        (!empty($data['details'])) ? $product->setDetails($data['details']) : '';
        /**
         * Date format: Y-m-d H:i
         */
        if(!empty($data['releaseDate'])) {
            $date = new \DateTime($data['releaseDate']);
            $product->setReleaseDate($date);
        }

        return $product;
    }

}
