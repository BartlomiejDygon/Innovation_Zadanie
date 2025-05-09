<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FindUserService
{
    public function __construct(private UserRepository $repository) {}

    public function getUserById(string $userId): ?User
    {
        $user = $this->repository->find($userId);
        if (!$user) {
            throw new NotFoundHttpException("Użytkownik o ID {$userId} nie został znaleziony.");
        }

        return $user;
    }
}
