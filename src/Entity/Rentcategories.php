<?php

namespace App\Entity;

use App\Repository\RentcategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentcategoriesRepository::class)]
class Rentcategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'rentCategoryId', targetEntity: Rentpark::class)]
    private Collection $categorySerialNumber;

    #[ORM\OneToMany(mappedBy: 'rentCategoryId', targetEntity: Rentpark::class)]
    private Collection $rentparks;

    public function __construct()
    {
        $this->categorySerialNumber = new ArrayCollection();
        $this->rentparks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Rentpark>
     */
    public function getCategorySerialNumber(): Collection
    {
        return $this->categorySerialNumber;
    }

    public function addCategorySerialNumber(Rentpark $categorySerialNumber): static
    {
        if (!$this->categorySerialNumber->contains($categorySerialNumber)) {
            $this->categorySerialNumber->add($categorySerialNumber);
            $categorySerialNumber->setRentCategoryId($this);
        }

        return $this;
    }

    public function removeCategorySerialNumber(Rentpark $categorySerialNumber): static
    {
        if ($this->categorySerialNumber->removeElement($categorySerialNumber)) {
            // set the owning side to null (unless already changed)
            if ($categorySerialNumber->getRentCategoryId() === $this) {
                $categorySerialNumber->setRentCategoryId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rentpark>
     */
    public function getRentparks(): Collection
    {
        return $this->rentparks;
    }

    public function addRentpark(Rentpark $rentpark): static
    {
        if (!$this->rentparks->contains($rentpark)) {
            $this->rentparks->add($rentpark);
            $rentpark->setRentCategoryId($this);
        }

        return $this;
    }

    public function removeRentpark(Rentpark $rentpark): static
    {
        if ($this->rentparks->removeElement($rentpark)) {
            // set the owning side to null (unless already changed)
            if ($rentpark->getRentCategoryId() === $this) {
                $rentpark->setRentCategoryId(null);
            }
        }

        return $this;
    }
}
