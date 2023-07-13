<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: ['username'],
    message: 'El usuario ya existe.',
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserGame::class, orphanRemoval: true)]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Invitation::class, orphanRemoval: true)]
    private Collection $invitationsSend;

    #[ORM\OneToMany(mappedBy: 'reciver', targetEntity: Invitation::class, orphanRemoval: true)]
    private Collection $InvitationsRecived;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: History::class, orphanRemoval: true)]
    private Collection $histories;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Ficha::class, orphanRemoval: true)]
    private Collection $fichas;

    public function __construct($id=null, $username=null, $password=null, $email=null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->games = new ArrayCollection();
        $this->invitationsSend = new ArrayCollection();
        $this->InvitationsRecived = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->fichas = new ArrayCollection();

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

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
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
            $history->setUser($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getUser() === $this) {
                $history->setUser(null);
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
            $ficha->setUser($this);
        }

        return $this;
    }

    public function removeFicha(Ficha $ficha): self
    {
        if ($this->fichas->removeElement($ficha)) {
            // set the owning side to null (unless already changed)
            if ($ficha->getUser() === $this) {
                $ficha->setUser(null);
            }
        }

        return $this;
    }
}
