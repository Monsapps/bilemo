<?php

namespace App\Model;

use JMS\Serializer\Annotation\Type;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
/**
 * @param PagerFanta $pagerFanta
 * @param UrlGeneratorInterface $router
 * @param string $entity for url generation [client|user]
 */
class User extends AbstractModel
{
    /**
     * @Type(value="array<App\Entity\User>")
     */
    public $data;

    public function __construct(Pagerfanta $pagerFanta, UrlGeneratorInterface $router, string $entity)
    {
        $this->data = $pagerFanta->getCurrentPageResults();

        $this->addMeta('limit', $pagerFanta->getMaxPerPage());
        $this->addMeta('current_items', count($pagerFanta->getCurrentPageResults()));
        $this->addMeta('total_items', $pagerFanta->getNbResults());
        $this->addMeta('total_pages', $pagerFanta->getNbPages());

        // Add Hypermedia
        if($pagerFanta->hasPreviousPage()) {
            $this->_links['previous_page']['href'] = $router->generate($entity . '_list', [
                'page' => $pagerFanta->getPreviousPage()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL);

        }

        $this->_links['current_page']['href'] = $router->generate($entity . '_list', [
            'page' => $pagerFanta->getCurrentPage()
        ],
        UrlGeneratorInterface::ABSOLUTE_URL);
        
        if($pagerFanta->hasNextPage()) {
            $this->_links['next_page']['href'] =  $router->generate($entity . '_list', [
                'page' => $pagerFanta->getNextPage()
            ],
        UrlGeneratorInterface::ABSOLUTE_URL);
        }
    }

}