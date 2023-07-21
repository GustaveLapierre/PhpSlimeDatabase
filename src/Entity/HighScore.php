<?php

namespace App\Entity;
use App\Repository\HighScoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HighScoreRepository::class)]
class HighScore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 512)]
    private $username;

    #[ORM\Column(type: 'string', length: 512, nullable: false, options: ["default" => "easy"])]
    private $gamemode;

    #[ORM\Column(type: 'float')]
    private $timer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getGamemode(): ?string
    {
        return $this->gamemode;
    }
    public function getTimer(): ?float
    {
        return $this->timer;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setGamemode(string $gamemode): self
    {
        $this->gamemode = $gamemode;
        return $this;
    }

    public function setTimer(float $timer): self
    {
        $this->timer = $timer;
        return $this;
    }

}