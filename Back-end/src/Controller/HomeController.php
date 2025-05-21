<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;

final class HomeController extends AbstractController
{
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/api/sample', name: 'api_sample', methods: ['GET'])]
    public function sampleApi(): JsonResponse
    {
        return new JsonResponse(['message' => 'Hello from Symfony API!']);
    }

    #[Route('/api/home', name: 'api_home', methods: ['GET'])]
    public function homeApi(): JsonResponse
    {
        return new JsonResponse(['message' => 'Welcome to the API!']);
    }

    #[Route('/{reactRouting}', name: 'react_frontend', requirements: ['reactRouting' => '.*'])]
    public function serveReactApp(): Response
    {
        $publicDir = $this->kernel->getProjectDir() . '/public';
        $indexPath = $publicDir . '/index.html';

        if (!file_exists($indexPath)) {
            throw $this->createNotFoundException('React app entry point not found.');
        }

        return new Response(file_get_contents($indexPath));
    }
}
