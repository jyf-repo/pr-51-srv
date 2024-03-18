<?php

namespace App\Entity;

use App\Repository\RentparkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentparkRepository::class)]
class Rentpark
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rentparks')]
    private ?Rentcategories $rentCategoryId = null;

    #[ORM\Column(length: 255)]
    private ?string $categorySerialNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startRent = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRentCategoryId(): ?Rentcategories
    {
        return $this->rentCategoryId;
    }

    public function setRentCategoryId(?Rentcategories $rentCategoryId): static
    {
        $this->rentCategoryId = $rentCategoryId;

        return $this;
    }

    public function getCategorySerialNumber(): ?string
    {
        return $this->categorySerialNumber;
    }

    public function setCategorySerialNumber(string $categorySerialNumber): static
    {
        $this->categorySerialNumber = $categorySerialNumber;

        return $this;
    }

    public function getStartRent(): ?\DateTimeInterface
    {
        return $this->startRent;
    }

    public function setStartRent(?\DateTimeInterface $startRent): static
    {
        $this->startRent = $startRent;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
