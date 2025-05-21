<?php
// src/Controller/Api/ContactController.php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'api_contact', methods: ['POST'])]
    public function send(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Here you would normally send an email or store the message
        // For demo, just return the received data
        return $this->json([
            'status' => 'success',
            'message' => 'Message reçu',
            'data' => $data,
        ]);
    }
}
