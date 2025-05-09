<?php
namespace App\Application\Command\WorkingTime;

use Symfony\Component\Validator\Constraints as Assert;

class GetWorkingTimeSummaryCommand
{
    #[Assert\NotBlank]
    #[Assert\Uuid(message: 'Nieprawidłowy format UUID.')]
    public string $userId;

    #[Assert\NotBlank(message: 'Data nie może być pusta.')]
    #[Assert\Regex(
        pattern: '/^\d{4}-\d{2}(-\d{2})?$/',
        message: 'Data musi być w formacie YYYY-MM lub YYYY-MM-DD.'
    )]
    public string $date;

    public function __construct(string $userId, string $date)
    {
        $this->userId = $userId;
        $this->date = $date;
    }
}