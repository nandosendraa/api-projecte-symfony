<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @method string getUserIdentifier()
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
class User implements \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read','reparation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    #[Groups(['reparation:read','user:read'])]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    #[Groups(['user:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 60)]
    #[Groups(['user:read'])]
    private ?string $lastNames = null;

    #[ORM\Column(length: 60)]
    #[Groups(['user:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read'])]
    private ?string $profile = null;

    #[ORM\Column(length: 40)]
    #[Groups(['user:read'])]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Reparation::class, orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $reparations;

    #[ORM\OneToMany(mappedBy: 'reparator', targetEntity: Reparation::class)]
    #[Groups(['user:read'])]
    private Collection $repairs;

    public function __construct()
    {
        $this->reparations = new ArrayCollection();
        $this->repairs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastNames(): ?string
    {
        return $this->lastNames;
    }

    public function setLastNames(string $lastNames): self
    {
        $this->lastNames = $lastNames;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Reparation>
     */
    public function getReparations(): Collection
    {
        return $this->reparations;
    }

    public function addReparation(Reparation $reparation): self
    {
        if (!$this->reparations->contains($reparation)) {
            $this->reparations->add($reparation);
            $reparation->setOwner($this);
        }

        return $this;
    }

    public function removeReparation(Reparation $reparation): self
    {
        if ($this->reparations->removeElement($reparation)) {
            // set the owning side to null (unless already changed)
            if ($reparation->getOwner() === $this) {
                $reparation->setOwner(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    /**
     * @return Collection<int, Reparation>
     */
    public function getRepairs(): Collection
    {
        return $this->repairs;
    }

    public function addRepair(Reparation $repair): self
    {
        if (!$this->repairs->contains($repair)) {
            $this->repairs->add($repair);
            $repair->setReparator($this);
        }

        return $this;
    }

    public function removeRepair(Reparation $repair): self
    {
        if ($this->repairs->removeElement($repair)) {
            // set the owning side to null (unless already changed)
            if ($repair->getReparator() === $this) {
                $repair->setReparator(null);
            }
        }

        return $this;
    }
}
