<?php
/**
 * This is a Product representation to share additionnal data
 * like pagination and self link
 */
namespace App\Model;

use JMS\Serializer\Annotation\Type;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Product
{
    /**
     * @Type(value="array<App\Entity\Product>")
     */
    public $data;
    public $pagination;
    public $meta;

    public function __construct(Pagerfanta $pagerFanta, UrlGeneratorInterface $router)
    {
        $this->data = $pagerFanta->getCurrentPageResults();

        $this->addMeta('limit', $pagerFanta->getMaxPerPage());
        $this->addMeta('current_items', count($pagerFanta->getCurrentPageResults()));
        $this->addMeta('total_items', $pagerFanta->getNbResults());
        $this->addMeta('total_pages', $pagerFanta->getNbPages());

        // Add Hypermedia
        if($pagerFanta->hasPreviousPage()) {
            $this->pagination['previous_page']['href'] = $router->generate('product_list', [
                'page' => $pagerFanta->getPreviousPage()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL);

        }

        $this->pagination['current_page']['href'] = $router->generate('product_list', [
            'page' => $pagerFanta->getCurrentPage()
        ],
        UrlGeneratorInterface::ABSOLUTE_URL);
        
        if($pagerFanta->hasNextPage()) {
            $this->pagination['next_page']['href'] =  $router->generate('product_list', [
                'page' => $pagerFanta->getNextPage()
            ],
        UrlGeneratorInterface::ABSOLUTE_URL);
        }
    }

    public function addMeta($name, $value)
    {
        if (isset($this->meta[$name])) {
            throw new \LogicException(sprintf('This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta.', $name));
        }
        
        $this->setMeta($name, $value);
    }
    
    public function setMeta($name, $value)
    {
        $this->meta[$name] = $value;
    }

}
