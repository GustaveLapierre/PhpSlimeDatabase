<?php

namespace App\Service;

use App\Repository\HighScoreRepository;
use App\Entity\HighScore;


class HighScoreManager
{
    private $highScoreRepository;

    public function __construct(HighScoreRepository $highScoreRepository)
    {
        $this->highScoreRepository = $highScoreRepository;
    }
    public function isExistUsername(string $username): bool
    {
        $user = $this->highScoreRepository->findOneBy(['username'=> $username]);
        return $user !== null;
    }

    public function isExistGamemode(string $gamemode, string $username): bool
    {
        $highScore = $this->highScoreRepository->findOneBy(['username'=> $username, 'gamemode'=> $gamemode]);
        return $highScore !== null;
    }

    public function isBetterTime(string $username, float $newTime, string $gamemode): bool
    {
        $highScore = $this->highScoreRepository->findOneBy([
            'username' => $username,
            'gamemode' => $gamemode
        ]);

        if ($highScore === null) {
            return false;
        }

        return $newTime > $highScore->getTimer();
    }

    public function getHighScoreByUsername(string $username, string $gamemode): HighScore
    {
        $highScore = $this->highScoreRepository->findOneBy([
            'username' => $username,
            'gamemode' => $gamemode
        ]);
        return $highScore;
    }



}