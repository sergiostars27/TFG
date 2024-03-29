<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $cover = null;

    #[ORM\Column(length: 255)]
    private ?string $GameSystem = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: UserGame::class, orphanRemoval: true)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Invitation::class, orphanRemoval: true)]
    private Collection $invitations;

    #[ORM\Column(nullable: true)]
    private array $imageList = [];

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messasges;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: History::class, orphanRemoval: true)]
    private Collection $histories;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Ficha::class, orphanRemoval: true)]
    private Collection $fichas;

    public function __construct($name=null,$cover=null,$GameSystem=null)
    {
        $this->name = $name;
        $this->cover = $cover;
        $this->GameSystem = $GameSystem;
        $this->users = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->messasges = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->fichas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getGameSystem(): ?string
    {
        return $this->GameSystem;
    }

    public function setGameSystem(string $GameSystem): self
    {
        $this->GameSystem = $GameSystem;

        return $this;
    }

    /**
     * @return Collection<int, UserGame>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserGame $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGame($this);
        }

        return $this;
    }

    public function removeUser(UserGame $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGame() === $this) {
                $user->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setGame($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getGame() === $this) {
                $invitation->setGame(null);
            }
        }

        return $this;
    }

    public function getImageList(): array
    {
        return $this->imageList;
    }

    public function setImageList(?array $imageList): self
    {
        $this->imageList = $imageList;

        return $this;
    }

    public function addImageList(string $image){
        array_push($this->imageList,$image);
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessasges(): Collection
    {
        return $this->messasges;
    }

    public function addMessasge(Message $messasge): self
    {
        if (!$this->messasges->contains($messasge)) {
            $this->messasges->add($messasge);
            $messasge->setGame($this);
        }

        return $this;
    }

    public function removeMessasge(Message $messasge): self
    {
        if ($this->messasges->removeElement($messasge)) {
            // set the owning side to null (unless already changed)
            if ($messasge->getGame() === $this) {
                $messasge->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories->add($history);
            $history->setGame($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getGame() === $this) {
                $history->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ficha>
     */
    public function getFichas(): Collection
    {
        return $this->fichas;
    }

    public function addFicha(Ficha $ficha): self
    {
        if (!$this->fichas->contains($ficha)) {
            $this->fichas->add($ficha);
            $ficha->setGame($this);
        }

        return $this;
    }

    public function removeFicha(Ficha $ficha): self
    {
        if ($this->fichas->removeElement($ficha)) {
            // set the owning side to null (unless already changed)
            if ($ficha->getGame() === $this) {
                $ficha->setGame(null);
            }
        }

        return $this;
    }
}
