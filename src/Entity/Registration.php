<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("registration")]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups("registration")]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("registration")]
    private ?string $status = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'registrations')]
    private Collection $player;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    private ?Tournament $tournament = null;

    public function __construct()
    {
        $this->player = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?\DateTimeInterface $registrationDate): static
    {
        $this->registrationDate = $registrationDate;

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
     * @return Collection<int, User>
     */
    public function getPlayer(): Collection
    {
        return $this->player;
    }

    public function addPlayer(User $player): static
    {
        if (!$this->player->contains($player)) {
            $this->player->add($player);
        }

        return $this;
    }

    public function removePlayer(User $player): static
    {
        $this->player->removeElement($player);

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }
}
