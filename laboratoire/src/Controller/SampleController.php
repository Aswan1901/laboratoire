<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


//Assign an equipment and a technician to samples and return result in json
final class SampleController extends AbstractController
{
    #[Route('/sample', name: 'app_sample')]
    public function index(): JsonResponse
    {



        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SampleController.php',
        ]);
    }
}
