<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CdrRepository;

#[ORM\Entity(repositoryClass: CdrRepository::class)]
class Cdr
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $customer_id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'text')]
    private $phone_number;

    #[ORM\Column(type: 'text')]
    private $customer_ip;

    #[ORM\Column(type: 'text', nullable: true)]
    private $origin_country;

    #[ORM\Column(type: 'text', nullable: true)]
    private $origin_continent;

    #[ORM\Column(type: 'text', nullable: true)]
    private $target_country;

    #[ORM\Column(type: 'text', nullable: true)]
    private $target_continent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDateString(string $date): self {
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $date);

        return $this->setDate($dateTime);
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getCustomerIp(): ?string
    {
        return $this->customer_ip;
    }

    public function setCustomerIp(string $customer_ip): self
    {
        $this->customer_ip = $customer_ip;

        return $this;
    }

    public function getOriginCountry(): ?string
    {
        return $this->origin_country;
    }

    public function setOriginCountry(?string $origin_country): self
    {
        $this->origin_country = $origin_country;

        return $this;
    }

    public function getOriginContinent(): ?string
    {
        return $this->origin_continent;
    }

    public function setOriginContinent(?string $origin_continent): self
    {
        $this->origin_continent = $origin_continent;

        return $this;
    }

    public function getTargetCountry(): ?string
    {
        return $this->target_country;
    }

    public function setTargetCountry(?string $target_country): self
    {
        $this->target_country = $target_country;

        return $this;
    }

    public function getTargetContinent(): ?string
    {
        return $this->target_continent;
    }

    public function setTargetContinent(?string $target_continent): self
    {
        $this->target_continent = $target_continent;

        return $this;
    }
}
