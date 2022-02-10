<?php

namespace App\Service;

use App\Entity\Product;
use App\Exception\ConstraintValidationException;
use App\Model\Product as ModelProduct;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{

    private $managerRegistry;
    private $productRepo;
    private $router;
    private $validator;

    public function __construct(
        ManagerRegistry $managerRegistry,
        ProductRepository $productRepo,
        UrlGeneratorInterface $router,
        ValidatorInterface $validator)
    {
        $this->managerRegistry = $managerRegistry;
        $this->productRepo = $productRepo;
        $this->router = $router;
        $this->validator = $validator;
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

        $this->productValidator($product);

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }

    public function editProduct(Product $product, array $data): Product
    {

        $this->addProductInfo($product, $data);

        $this->productValidator($product);

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

    private function productValidator(Product $product): void
    {

        $violations = $this->validator->validate($product);

        if(count($violations)) {

            throw new ValidationFailedException($product, $violations);
        }
    }

}
