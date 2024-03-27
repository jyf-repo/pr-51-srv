<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $prescription_date = null;

    #[ORM\ManyToOne(inversedBy: 'prescriptions')]
    private ?Doctor $doctor = null;

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

    public function getPrescriptionDate(): ?\DateTimeInterface
    {
        return $this->prescription_date;
    }

    public function setPrescriptionDate(?\DateTimeInterface $prescription_date): static
    {
        $this->prescription_date = $prescription_date;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

}
