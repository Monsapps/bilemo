<?php

namespace App\Service;

use App\Entity\Product;
use App\Model\Product as ModelProduct;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        $product->setName($data['name']);
        $product->setBrand($data['brand']);
        $product->setDetails($data['details']);
        /**
         * Date format: Y-m-d H:i
         */
        $date = new \DateTime($data['releaseDate']);
        $product->setReleaseDate($date);

        $violations = $this->validator->validate($product);

        if(count($violations)) {
            $message = "Houston we have a problem!\n";

            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s \n", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new Exception($message);
        }

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }

}
