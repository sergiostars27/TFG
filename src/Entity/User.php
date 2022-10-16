<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserGame::class, orphanRemoval: true)]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Images::class, orphanRemoval: true)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Record::class, orphanRemoval: true)]
    private Collection $records;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Invitation::class, orphanRemoval: true)]
    private Collection $invitationsSend;

    #[ORM\OneToMany(mappedBy: 'reciver', targetEntity: Invitation::class, orphanRemoval: true)]
    private Collection $InvitationsRecived;

    public function __construct($id=null, $username=null, $password=null, $email=null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->games = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->records = new ArrayCollection();
        $this->invitationsSend = new ArrayCollection();
        $this->InvitationsRecived = new ArrayCollection();

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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
     * @return Collection<int, UserGame>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(UserGame $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setUser($this);
        }

        return $this;
    }

    public function removeGame(UserGame $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getUser() === $this) {
                $game->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setUser($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Record>
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addRecord(Record $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records->add($record);
            $record->setUser($this);
        }

        return $this;
    }

    public function removeRecord(Record $record): self
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getUser() === $this) {
                $record->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitationsSend(): Collection
    {
        return $this->invitationsSend;
    }

    public function addInvitationsSend(Invitation $invitationsSend): self
    {
        if (!$this->invitationsSend->contains($invitationsSend)) {
            $this->invitationsSend->add($invitationsSend);
            $invitationsSend->setSender($this);
        }

        return $this;
    }

    public function removeInvitationsSend(Invitation $invitationsSend): self
    {
        if ($this->invitationsSend->removeElement($invitationsSend)) {
            // set the owning side to null (unless already changed)
            if ($invitationsSend->getSender() === $this) {
                $invitationsSend->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitationsRecived(): Collection
    {
        return $this->InvitationsRecived;
    }

    public function addInvitationsRecived(Invitation $invitationsRecived): self
    {
        if (!$this->InvitationsRecived->contains($invitationsRecived)) {
            $this->InvitationsRecived->add($invitationsRecived);
            $invitationsRecived->setReciver($this);
        }

        return $this;
    }

    public function removeInvitationsRecived(Invitation $invitationsRecived): self
    {
        if ($this->InvitationsRecived->removeElement($invitationsRecived)) {
            // set the owning side to null (unless already changed)
            if ($invitationsRecived->getReciver() === $this) {
                $invitationsRecived->setReciver(null);
            }
        }

        return $this;
    }
}
