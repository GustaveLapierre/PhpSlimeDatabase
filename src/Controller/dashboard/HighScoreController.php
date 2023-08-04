<?php

namespace App\Controller\dashboard;

use App\Entity\HighScore;
use App\Repository\HighScoreRepository;
use App\Service\HighScoreManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;




class HighScoreController extends AbstractController
{
    public function __construct(private HighScoreManager $highScoreManager)
    {
    }

    #[Route('/dashboard', name: 'get_dashboard_high_scores', methods: 'GET')]
    public function index(HighScoreRepository $highScoreRepository): Response
    {
        $highScores = $highScoreRepository->findAll();

        // Render the Twig view and pass the high scores to it
        return $this->render('dashboard/index.html.twig', [
            'high_scores' => $highScores,
        ]);
    }

    #[Route('/dashboard/delete/{username}/{gamemode}', name: 'delete_dashboard_high_scores', methods: 'GET')]
    public function delete(string $username, string $gamemode, HighScoreRepository $highScoreRepository, EntityManagerInterface $em): Response
    {
        $highScores = $highScoreRepository->findBy(['username' => $username, 'gamemode' => $gamemode]);

        if (!$highScores) {
            // Add flash message
            $this->addFlash('error', 'No high scores found for this username');

            // Redirect back to the dashboard
            return $this->redirectToRoute('get_high_scores');
        }

        foreach ($highScores as $highScore) {
            $em->remove($highScore);
        }

        $em->flush();

        // Add flash message
        $this->addFlash('success', 'High scores successfully deleted');

        // Redirect back to the dashboard
        return $this->redirectToRoute('get_dashboard_high_scores');
    }

}
