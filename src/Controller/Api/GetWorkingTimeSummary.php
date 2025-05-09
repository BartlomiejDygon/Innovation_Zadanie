<?php
namespace App\Controller\Api;

use App\Application\Command\WorkingTime\GetWorkingTimeSummaryCommand;
use App\Application\Handler\WorkingTime\GetWorkingTimeSummaryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/working-time-summary/get', name: 'api_working_time_summary_get', methods: ['POST'])]

class GetWorkingTimeSummary extends AbstractController
{
    public function __invoke(
        Request $request,
        GetWorkingTimeSummaryHandler $handler
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['userId'], $data['date'])) {
            return $this->json(['error' => 'Brak wymaganych danych: userId i date'], 400);
        }

        try {
            $command = new GetWorkingTimeSummaryCommand(
                $data['userId'],
                $data['date']
            );
            $result = $handler($command);

            return $this->json($result, 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}