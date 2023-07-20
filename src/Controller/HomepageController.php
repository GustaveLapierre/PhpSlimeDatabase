<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class HomepageController
{
    #[Route('/', name: 'app_homePage', methods: 'GET')]
    public function getHomePage(): Response
    {
        return new Response(

            "Hello World",
            200,
        );
    }
}