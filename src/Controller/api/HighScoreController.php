<?php

namespace App\Controller\api;

use App\Entity\HighScore;
use App\Repository\HighScoreRepository;
use App\Service\HighScoreManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HighScoreController extends AbstractController
{
    public function __construct(private HighScoreManager $highScoreManager)
    {
    }

    #[Route('/api', name: 'get_api_high_scores', methods: 'GET')]
    public function index(HighScoreRepository $highScoreRepository): Response
    {
        $highScores = $highScoreRepository->findAll();
        return $this->json(['dashboard' => $highScores]);
    }
    #[Route('/api', name: 'create_api_high_score', methods: 'POST')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $username = $data["username"];
        $timer = $data["timer"];

        $highScore = $this->highScoreManager->isExistUsername($username) ?
            $this->highScoreManager->getHighScoreByUsername($username) : new HighScore();


        if (!$this->highScoreManager->isExistUsername($username) ||
            $this->highScoreManager->isBetterTime($username, $timer)) {
            $highScore->setUsername($username);
            $highScore->setTimer($timer);
            $em->persist($highScore);
            $em->flush();
        }

        return $this->json(['id' => $highScore->getId()]);
    }

}