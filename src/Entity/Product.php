<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table
 * 
 * @Hateoas\Relation(
 *      name = "self",
 *      href = @Hateoas\Route(
 *          "product_details",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"Default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"Default", "Details"})
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"Default", "Details"})
     */
    private $brand;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"Details"})
     */
    private $details;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"Details"})
     */
    private $releaseDate;

    public function __construct(string $name, string $brand, string $details, \DateTime $releaseDate)
    {
        $this->name = $name;
        $this->brand = $brand;
        $this->details = $details;
        $this->releaseDate = $releaseDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

}
