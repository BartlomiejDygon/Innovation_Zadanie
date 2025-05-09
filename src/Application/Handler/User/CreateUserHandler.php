<?php
namespace App\Application\Handler\User;

use App\Application\Command\User\CreateUserCommand;
use App\Entity\User;
use App\Service\CommandValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateUserHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private CommandValidationService $validationService
    ){}
    public function __invoke(CreateUserCommand $command): User
    {
        $this->validationService->validate($command);

        $existingUser = $this->em->getRepository(User::class)->findOneBy([
            'firstName' => $command->firstName,
            'lastName' => $command->lastName,
        ]);

        if ($existingUser) {
            throw new BadRequestHttpException('Użytkownik już istnieje');
        }

        $user = new User();
        $user->setFirstName($command->firstName);
        $user->setLastName($command->lastName);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}