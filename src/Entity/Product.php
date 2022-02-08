<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table
 * @Serializer\ExclusionPolicy("ALL")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose
     */
    private $brand;

    /**
     * @ORM\Column(type="string")
     */
    private $details;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    public function __construct(string $name, string $brand, string $details, \DateTime $releaseDate)
    {
        $this->name = $name;
        $this->brand = $brand;
        $this->details = $details;
        $this->releaseDate = $releaseDate;
    }

}
