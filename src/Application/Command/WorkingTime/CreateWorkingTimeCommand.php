<?php
namespace App\Application\Command\WorkingTime;

use Symfony\Component\Validator\Constraints as Assert;

class CreateWorkingTimeCommand
{
    #[Assert\NotBlank]
    #[Assert\Uuid(message: 'Nieprawidłowy format UUID.')]
    public string $userId;

    #[Assert\NotBlank]
    #[Assert\Type("\DateTime")]
    public \DateTime $startingWork;

    #[Assert\NotBlank]
    #[Assert\Type("\DateTime")]
    public \DateTime $endingWork;

    public function __construct(
        string $userId,
        \DateTime $startingWork,
        \DateTime $endingWork
    ) {
        $this->userId = $userId;
        $this->startingWork = $startingWork;
        $this->endingWork = $endingWork;
    }
}