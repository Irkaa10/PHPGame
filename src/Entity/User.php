<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["tournament", "user", "game"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("user")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("user")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("user")]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("user")]
    private ?string $emailAdress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("user")]
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: Tournament::class, mappedBy: 'organizer')]
    private Collection $organizedTournaments;

    #[ORM\OneToMany(targetEntity: Tournament::class, mappedBy: 'winner')]
    private Collection $wonTournaments;

    #[ORM\ManyToMany(targetEntity: Registration::class, mappedBy: 'player')]
    private Collection $registrations;

    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'player1')]
    private Collection $games;

    public function __construct()
    {
        $this->organizedTournaments = new ArrayCollection();
        $this->wonTournaments = new ArrayCollection();
        $this->registrations = new ArrayCollection();
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmailAdress(): ?string
    {
        return $this->emailAdress;
    }

    public function setEmailAdress(?string $emailAdress): static
    {
        $this->emailAdress = $emailAdress;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getOrganizedTournaments(): Collection
    {
        return $this->organizedTournaments;
    }

    public function addOrganizedTournament(Tournament $organizedTournament): static
    {
        if (!$this->organizedTournaments->contains($organizedTournament)) {
            $this->organizedTournaments->add($organizedTournament);
            $organizedTournament->setOrganizer($this);
        }

        return $this;
    }

    public function removeOrganizedTournament(Tournament $organizedTournament): static
    {
        if ($this->organizedTournaments->removeElement($organizedTournament)) {
            // set the owning side to null (unless already changed)
            if ($organizedTournament->getOrganizer() === $this) {
                $organizedTournament->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getWonTournaments(): Collection
    {
        return $this->wonTournaments;
    }

    public function addWonTournament(Tournament $wonTournament): static
    {
        if (!$this->wonTournaments->contains($wonTournament)) {
            $this->wonTournaments->add($wonTournament);
            $wonTournament->setWinner($this);
        }

        return $this;
    }

    public function removeWonTournament(Tournament $wonTournament): static
    {
        if ($this->wonTournaments->removeElement($wonTournament)) {
            // set the owning side to null (unless already changed)
            if ($wonTournament->getWinner() === $this) {
                $wonTournament->setWinner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): static
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->addPlayer($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            $registration->removePlayer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setPlayer1($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getPlayer1() === $this) {
                $game->setPlayer1(null);
            }
        }

        return $this;
    }

// Ou toute autre propriété qui représente de manière unique l'utilisateur
    public function __toString(): string
    {
        return $this->username;
    }
}

