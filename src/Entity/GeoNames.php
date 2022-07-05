<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GeoNamesRepository;

#[ORM\Entity(repositoryClass: GeoNamesRepository::class)]
class GeoNames
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $iso;

    #[ORM\Column(type: 'text')]
    private $country;

    #[ORM\Column(type: 'text')]
    private $continent;

    #[ORM\Column(type: 'text', nullable: true)]
    private $phone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIso(): ?string
    {
        return $this->iso;
    }

    public function setIso(string $iso): self
    {
        $this->iso = $iso;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getContinent(): ?string
    {
        return $this->continent;
    }

    public function setContinent(string $continent): self
    {
        $this->continent = $continent;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
