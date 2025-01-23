<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    private ?Location $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[Assert\Type(\DateTimeInterface::class, groups: ['create', 'edit'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 0)]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[Assert\Range(min: -50, max: 50, groups: ['create', 'edit'])]
    private ?string $celsius = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCelsius(): ?string
    {
        return $this->celsius;
    }

    public function setCelsius(string $celsius): static
    {
        $this->celsius = $celsius;

        return $this;
    }

    public function getFahrenheit(): float
    {
        return ($this->getCelsius() * 9/5) + 32;
    }

}
