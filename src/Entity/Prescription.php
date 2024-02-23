<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("USER_PRESCRIPTION")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'prescriptions')]
    private ?User $userId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("USER_PRESCRIPTION")]
    private ?string $prescriptionFileName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("USER_PRESCRIPTION")]
    private ?string $prescription_path = null;

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

    public function getPrescriptionPath(): ?string
    {
        return $this->prescription_path;
    }

    public function setPrescriptionPath(?string $prescription_path): static
    {
        $this->prescription_path = $prescription_path;

        return $this;
    }

}
