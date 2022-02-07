<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
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
     */
    private $name;

    /**
     * @ORM\Column(type="string")
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
