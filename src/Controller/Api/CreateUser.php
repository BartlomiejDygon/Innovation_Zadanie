<?php
namespace App\Controller\Api;

use App\Application\Command\User\CreateUserCommand;
use App\Application\Handler\User\CreateUserHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/user/add', name: 'api_user_add', methods: ['POST'])]
class CreateUser extends AbstractController
{
    public function __invoke(
        Request $request,
        CreateUserHandler $handler
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'] ?? null;
        $lastName = $data['lastName'] ?? null;

        $command = new CreateUserCommand($firstName, $lastName);

        try {
            $user = $handler($command);

            return $this->json([
                'userID' => $user->getId()
            ], 201);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}