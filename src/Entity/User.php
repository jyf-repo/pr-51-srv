<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $api_key_auth = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phonenumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clientToken = null;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Prescription::class)]
    private Collection $prescriptions;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Pillbox::class, orphanRemoval: true)]
    private Collection $pillboxes;


    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
        $this->pillboxes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getApiKeyAuth(): ?string
    {
        return $this->api_key_auth;
    }

    public function setApiKeyAuth(string $api_key_auth): static
    {
        $this->api_key_auth = $api_key_auth;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getClientToken(): ?string
    {
        return $this->clientToken;
    }

    public function setClientToken(?string $clientToken): static
    {
        $this->clientToken = $clientToken;

        return $this;
    }

    /**
     * @return Collection<int, Prescription>
     */
    public function getPrescriptions(): Collection
    {
        return $this->prescriptions;
    }

    public function addPrescription(Prescription $prescription): static
    {
        if (!$this->prescriptions->contains($prescription)) {
            $this->prescriptions->add($prescription);
            $prescription->setUserId($this);
        }

        return $this;
    }

    public function removePrescription(Prescription $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getUserId() === $this) {
                $prescription->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pillbox>
     */
    public function getPillboxes(): Collection
    {
        return $this->pillboxes;
    }

    public function addPillbox(Pillbox $pillbox): static
    {
        if (!$this->pillboxes->contains($pillbox)) {
            $this->pillboxes->add($pillbox);
            $pillbox->setUserId($this);
        }

        return $this;
    }

    public function removePillbox(Pillbox $pillbox): static
    {
        if ($this->pillboxes->removeElement($pillbox)) {
            // set the owning side to null (unless already changed)
            if ($pillbox->getUserId() === $this) {
                $pillbox->setUserId(null);
            }
        }

        return $this;
    }
}
