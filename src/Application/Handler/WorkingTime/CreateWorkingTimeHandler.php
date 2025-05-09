<?php
namespace App\Application\Handler\WorkingTime;

use App\Application\Command\WorkingTime\CreateWorkingTimeCommand;
use App\Entity\WorkingTime;
use App\Service\CommandValidationService;
use App\Service\FindUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateWorkingTimeHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private CommandValidationService $validationService,
        private FindUserService $findUserService,
    ) {}

    public function __invoke(CreateWorkingTimeCommand $command): WorkingTime
    {
        $this->validationService->validate($command);
        $user = $this->findUserService->getUserById($command->userId);

        $startDate = \DateTime::createFromInterface($command->startingWork)->setTime(0,0);

        $existingWorkingTime = $this->em->getRepository(WorkingTime::class)->findOneBy([
            'user' => $user,
            'startDate' => $startDate,
        ]);

        if ($existingWorkingTime) {
            throw new BadRequestHttpException("Użytkownik już ma zarejestrowany czas pracy dla dnia " . $startDate->format('Y-m-d') . ".");
        }

        $interval = $command->startingWork->diff($command->endingWork);
        $hours = (int)$interval->format('%h') + ($interval->days * 24);

        if ($hours > WorkingTime::MAX_HOURS_PER_SHIFT) {
            throw new BadRequestHttpException("Czas pracy nie może przekraczać 12 godzin. Zarejestrowano: {$hours} godz.");
        }

        $workingTime = new WorkingTime();
        $workingTime->setUser($user);
        $workingTime->setStartingWork($command->startingWork);
        $workingTime->setEndingWork($command->endingWork);
        $workingTime->setStartDate($startDate);

        $this->em->persist($workingTime);
        $this->em->flush();

        return $workingTime;
    }
}