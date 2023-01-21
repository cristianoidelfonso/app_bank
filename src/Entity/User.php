<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'createdByUser', targetEntity: Banco::class)]
    private Collection $creatorOfBank;

    #[ORM\OneToMany(mappedBy: 'createdByUser', targetEntity: Agencia::class)]
    private Collection $agencyCreator;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Conta::class, orphanRemoval: true)]
    private Collection $contas;

    public function __construct()
    {
        $this->creatorOfBank = new ArrayCollection();
        $this->agencyCreator = new ArrayCollection();
        $this->contas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Banco>
     */
    public function getCreatorOfBank(): Collection
    {
        return $this->creatorOfBank;
    }

    public function addCreatorOfBank(Banco $creatorOfBank): self
    {
        if (!$this->creatorOfBank->contains($creatorOfBank)) {
            $this->creatorOfBank->add($creatorOfBank);
            $creatorOfBank->setCreatedByUser($this);
        }

        return $this;
    }

    public function removeCreatorOfBank(Banco $creatorOfBank): self
    {
        if ($this->creatorOfBank->removeElement($creatorOfBank)) {
            // set the owning side to null (unless already changed)
            if ($creatorOfBank->getCreatedByUser() === $this) {
                $creatorOfBank->setCreatedByUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Agencia>
     */
    public function getAgencyCreator(): Collection
    {
        return $this->agencyCreator;
    }

    public function addAgencyCreator(Agencia $agencyCreator): self
    {
        if (!$this->agencyCreator->contains($agencyCreator)) {
            $this->agencyCreator->add($agencyCreator);
            $agencyCreator->setCreatedByUser($this);
        }

        return $this;
    }

    public function removeAgencyCreator(Agencia $agencyCreator): self
    {
        if ($this->agencyCreator->removeElement($agencyCreator)) {
            // set the owning side to null (unless already changed)
            if ($agencyCreator->getCreatedByUser() === $this) {
                $agencyCreator->setCreatedByUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conta>
     */
    public function getContas(): Collection
    {
        return $this->contas;
    }

    public function addConta(Conta $conta): self
    {
        if (!$this->contas->contains($conta)) {
            $this->contas->add($conta);
            $conta->setUser($this);
        }

        return $this;
    }

    public function removeConta(Conta $conta): self
    {
        if ($this->contas->removeElement($conta)) {
            // set the owning side to null (unless already changed)
            if ($conta->getUser() === $this) {
                $conta->setUser(null);
            }
        }

        return $this;
    }

}
