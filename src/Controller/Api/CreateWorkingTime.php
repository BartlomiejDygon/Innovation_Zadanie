<?php
namespace App\Controller\Api;

use App\Application\Command\WorkingTime\CreateWorkingTimeCommand;
use App\Application\Handler\WorkingTime\CreateWorkingTimeHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/working-time/add', name: 'api_working_time_add', methods: ['POST'])]
class CreateWorkingTime extends AbstractController
{
    public function __invoke(
        Request $request,
        CreateWorkingTimeHandler $handler,
    ) : JsonResponse {
        $data = json_decode($request->getContent(), true);

        try {
            $command = new CreateWorkingTimeCommand(
                userId : $data['userId'] ?? '',
                startingWork: new \DateTime($data['startingWork'] ?? ''),
                endingWork: new \DateTime($data['endingWork'] ?? '')
            );

            $handler($command);

            return $this->json([
                'message' => 'Czas pracy został dodany!'
            ], 201);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}