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

    public function isBetterTime(string $username, float $newTime): bool
    {
        $highScore = $this->highScoreRepository->findOneByUsername($username);

        if ($highScore === null) {
            return false;
        }

        return $newTime > $highScore->getTimer();
    }

    public function getHighScoreByUsername(string $username): HighScore
    {
        return $this->highScoreRepository->findOneBy(['username' => $username]);
    }



}