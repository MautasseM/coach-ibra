<?php
// src/Controller/Api/ScheduleController.php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{
    #[Route('/api/schedule', name: 'api_schedule', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $schedule = [
            [
                'id' => 1,
                'day' => 'Lundi',
                'time' => '18:00 - 19:00',
                'program' => 'Coaching Personnel',
                'location' => 'Salle Principale',
            ],
            [
                'id' => 2,
                'day' => 'Mardi',
                'time' => '19:00 - 20:00',
                'program' => 'Cours Collectifs',
                'location' => 'Salle 2',
            ],
            [
                'id' => 3,
                'day' => 'Mercredi',
                'time' => '17:00 - 18:00',
                'program' => 'Programme Enfants',
                'location' => 'Salle Enfants',
            ],
        ];
        return $this->json($schedule);
    }
}
