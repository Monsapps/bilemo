<?php
/**
 * This is a Product representation to share additionnal data
 * like pagination and self link
 */
namespace App\Model;

use JMS\Serializer\Annotation\Type;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Product extends AbstractModel
{
    /**
     * @Type(value="array<App\Entity\Product>")
     */
    public $data;

    public function __construct(Pagerfanta $pagerFanta, UrlGeneratorInterface $router)
    {
        $this->data = $pagerFanta->getCurrentPageResults();

        $this->addMeta('limit', $pagerFanta->getMaxPerPage());
        $this->addMeta('current_items', count($pagerFanta->getCurrentPageResults()));
        $this->addMeta('total_items', $pagerFanta->getNbResults());
        $this->addMeta('total_pages', $pagerFanta->getNbPages());

        // Add Hypermedia
        if($pagerFanta->hasPreviousPage()) {
            $this->_links['previous_page']['href'] = $router->generate('product_list', [
                'page' => $pagerFanta->getPreviousPage()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL);

        }

        $this->_links['current_page']['href'] = $router->generate('product_list', [
            'page' => $pagerFanta->getCurrentPage()
        ],
        UrlGeneratorInterface::ABSOLUTE_URL);
        
        if($pagerFanta->hasNextPage()) {
            $this->_links['next_page']['href'] =  $router->generate('product_list', [
                'page' => $pagerFanta->getNextPage()
            ],
        UrlGeneratorInterface::ABSOLUTE_URL);
        }
    }

}
