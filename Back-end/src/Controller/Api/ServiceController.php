<?php
// src/Controller/Api/ServiceController.php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/api/services', name: 'api_services', methods: ['GET'])]
    public function index(): JsonResponse
    {
        // Full data for each service
        $services = [
            [
                'id' => 1,
                'name' => 'Coaching Personnel',
                'description' => 'Un accompagnement sur-mesure pour atteindre vos objectifs.',
                'pricing' => [
                    'Séance unique : 60€',
                    'Forfait 5 séances : 275€ (55€/séance)',
                    'Forfait 10 séances : 500€ (50€/séance)',
                    'Mensuel illimité (3x/semaine) : 550€',
                ],
            ],
            [
                'id' => 2,
                'name' => 'Cours Collectifs',
                'description' => 'Des séances dynamiques en groupe pour se motiver ensemble.',
                'pricing' => [
                    'Cours à l’unité : 25€',
                    'Carte 5 cours : 110€ (22€/cours)',
                    'Carte 10 cours : 200€ (20€/cours)',
                    'Mensuel illimité : 220€',
                ],
            ],
            [
                'id' => 3,
                'name' => 'Programme Enfants',
                'description' => 'Des activités adaptées pour les plus jeunes.',
                'pricing' => [
                    'Cours à l’unité : 20€',
                    'Carte 5 cours : 90€ (18€/cours)',
                    'Carte 10 cours : 160€ (16€/cours)',
                    'Mensuel illimité (2x/semaine) : 150€',
                ],
            ],
        ];
        return $this->json($services);
    }
}
