<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'prescriptions')]
    private ?User $userId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prescriptionFileName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getPrescriptionFileName(): ?string
    {
        return $this->prescriptionFileName;
    }

    public function setPrescriptionFileName(?string $prescriptionFileName): static
    {
        //dd($prescriptionFileName);
        $this->prescriptionFileName = $prescriptionFileName;

        return $this;
    }

}
