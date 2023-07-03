<?php

namespace App\Entity;

use App\Repository\FichaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichaRepository::class)]
class Ficha
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $characterName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $age = null;

    #[ORM\ManyToOne(inversedBy: 'fichas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'fichas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\OneToMany(mappedBy: 'ficha', targetEntity: Attributes::class, orphanRemoval: true)]
    private Collection $atributos;

    #[ORM\Column(length: 255)]
    private ?string $sexo = null;

    public function __construct()
    {
        $this->atributos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacterName(): ?string
    {
        return $this->characterName;
    }

    public function setCharacterName(string $characterName): self
    {
        $this->characterName = $characterName;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection<int, Attributes>
     */
    public function getAtributos(): Collection
    {
        return $this->atributos;
    }

    public function addAtributo(Attributes $atributo): self
    {
        if (!$this->atributos->contains($atributo)) {
            $this->atributos->add($atributo);
            $atributo->setFicha($this);
        }

        return $this;
    }

    public function removeAtributo(Attributes $atributo): self
    {
        if ($this->atributos->removeElement($atributo)) {
            // set the owning side to null (unless already changed)
            if ($atributo->getFicha() === $this) {
                $atributo->setFicha(null);
            }
        }

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }
}
