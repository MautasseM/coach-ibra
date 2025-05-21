<?php
// src/Controller/Api/TestimonialController.php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestimonialController extends AbstractController
{
    #[Route('/api/testimonials', name: 'api_testimonials', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $testimonials = [
            [
                'id' => 1,
                'author' => 'Alice',
                'content' => "Coach Ibra m'a aidé à transformer ma vie!",
            ],
            [
                'id' => 2,
                'author' => 'Karim',
                'content' => "Des cours variés et une super ambiance.",
            ],
        ];
        return $this->json($testimonials);
    }
}
