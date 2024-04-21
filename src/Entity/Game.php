<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("game")]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups("game")]
    private ?\DateTimeInterface $gameDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups("game")]
    private ?int $scorePlayer1 = null;

    #[ORM\Column(nullable: true)]
    #[Groups("game")]
    private ?int $scorePlayer2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("game")]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[Groups("game")]
    private ?Tournament $tournament = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[Groups("game")]
    private ?User $player1 = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[Groups("game")]
    private ?User $player2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameDate(): ?\DateTimeInterface
    {
        return $this->gameDate;
    }

    public function setGameDate(?\DateTimeInterface $gameDate): static
    {
        $this->gameDate = $gameDate;

        return $this;
    }

    public function getScorePlayer1(): ?int
    {
        return $this->scorePlayer1;
    }

    public function setScorePlayer1(?int $scorePlayer1): static
    {
        $this->scorePlayer1 = $scorePlayer1;

        return $this;
    }

    public function getScorePlayer2(): ?int
    {
        return $this->scorePlayer2;
    }

    public function setScorePlayer2(?int $scorePlayer2): static
    {
        $this->scorePlayer2 = $scorePlayer2;

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

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getPlayer1(): ?User
    {
        return $this->player1;
    }

    public function setPlayer1(?User $player1): static
    {
        $this->player1 = $player1;

        return $this;
    }

    public function getPlayer2(): ?User
    {
        return $this->player2;
    }

    public function setPlayer2(?User $player2): static
    {
        $this->player2 = $player2;

        return $this;
    }
}
