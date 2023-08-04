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
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);
        $username = $data["username"];
        $timer = $data["timer"];
        $gamemode = $data["gamemode"];



        $highScore = $this->highScoreManager->isExistGamemode($gamemode, $username)
            ? $this->highScoreManager->getHighScoreByUsername($username, $gamemode)
            : new HighScore();


        if (!$this->highScoreManager->isExistGamemode($gamemode, $username) ||
            $this->highScoreManager->isBetterTime($username, $timer, $gamemode)) {
            $highScore->setUsername($username);
            $highScore->setTimer($timer);
            $highScore->SetGamemode($gamemode);

            $errors = $validator->validate($highScore);

            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                $errorsString = (string) $errors;

                return new Response($errorsString);
            }

            $em->persist($highScore);
            $em->flush();
        }


        return $this->json(['id' => $highScore->getId()]);
    }



}