<?php

namespace App\Entity;

use App\Repository\BancoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BancoRepository::class)]
class Banco
{
    public const CREATE = 'BANCO_CREATE';
    public const EDIT   = 'BANCO_EDIT';
    public const VIEW   = 'BANCO_VIEW';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'banco', targetEntity: Agencia::class, orphanRemoval: true)]
    private Collection $agencias;

    public function __construct()
    {
        $this->agencias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return Collection<int, Agencia>
     */
    public function getAgencias(): Collection
    {
        return $this->agencias;
    }

    public function addAgencia(Agencia $agencia): self
    {
        if (!$this->agencias->contains($agencia)) {
            $this->agencias->add($agencia);
            $agencia->setBanco($this);
        }

        return $this;
    }

    public function removeAgencia(Agencia $agencia): self
    {
        if ($this->agencias->removeElement($agencia)) {
            // set the owning side to null (unless already changed)
            if ($agencia->getBanco() === $this) {
                $agencia->setBanco(null);
            }
        }

        return $this;
    }
}
