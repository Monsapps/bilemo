<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Asserts;

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
     * 
     * @Serializer\Groups({"Default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * 
     * @Serializer\Groups({"Default", "Details"})
     * 
     * @Asserts\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * 
     * @Serializer\Groups({"Default", "Details"})
     * 
     * @Asserts\NotBlank
     */
    private $brand;

    /**
     * @ORM\Column(type="string")
     * 
     * @Serializer\Groups({"Details"})
     * 
     * @Asserts\NotBlank
     */
    private $details;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Serializer\Groups({"Details"})
     * 
     * @Asserts\Type("\DateTimeInterface")
     * 
     * @Asserts\NotBlank
     * 
     */
    private $releaseDate;

    /*public function __construct(string $name, string $brand, string $details, \DateTime $releaseDate)
    {
        $this->name = $name;
        $this->brand = $brand;
        $this->details = $details;
        $this->releaseDate = $releaseDate;
    }*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getReleaseDate(): \DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTime $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

}
