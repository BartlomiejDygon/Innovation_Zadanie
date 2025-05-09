<?php
namespace App\Application\Handler\WorkingTime;

use App\Application\Command\WorkingTime\GetWorkingTimeSummaryCommand;
use App\Entity\WorkingTime;
use App\Repository\WorkingTimeRepository;
use App\Service\CommandValidationService;
use App\Service\FindUserService;

class GetWorkingTimeSummaryHandler
{
    public function __construct(
        private CommandValidationService $validationService,
        private FindUserService $findUserService,
        private WorkingTimeRepository $repository,
    ) {}

    public function __invoke(GetWorkingTimeSummaryCommand $command): array
    {
        $this->validationService->validate($command);
        $user = $this->findUserService->getUserById($command->userId);

        if (preg_match('/^\d{4}-\d{2}$/', $command->date)) {
            $startDate = new \DateTime($command->date);
            $startDate->modify('first day of this month');
            $endDate = (clone $startDate)->modify('last day of this month');
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $command->date)) {
            $startDate = new \DateTime($command->date);
            $endDate = clone $startDate;
        } else {
            throw new \InvalidArgumentException('Nieprawidłowy format daty. Dozwolone: YYYY-MM lub YYYY-MM-DD.');
        }

        $records = $this->repository->findForUserInDateRange($user, $startDate, $endDate);

        $totalMinutes = 0;

        foreach ($records as $record) {
            $start = $record->getStartingWork();
            $end = $record->getEndingWork();

            $interval = $start->diff($end);
            $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

            $roundedMinutes = round($minutes / 30) * 30;
            $totalMinutes += $roundedMinutes;
        }

        $totalRoundedHours = round($totalMinutes / 60, 1);

        $totalBasicHour = $totalRoundedHours > WorkingTime::MONTHLY_NORM_OF_HOURS ?
            WorkingTime::MONTHLY_NORM_OF_HOURS : $totalRoundedHours;

        $totalOvertimeHours = $totalRoundedHours > WorkingTime::MONTHLY_NORM_OF_HOURS ?
            $totalRoundedHours - WorkingTime::MONTHLY_NORM_OF_HOURS : 0;

        $basicPay = $totalBasicHour * WorkingTime::HOURLY_RATE;
        $overtimePey = $totalOvertimeHours * (WorkingTime::HOURLY_RATE * 2);

        if ($totalOvertimeHours) {
            $result = [
                'userId' => $command->userId,
                'Zakres daty' => $command->date,
                'Ilość normalnych godzin z danego miesiąca' => $totalBasicHour,
                'Stawka' => WorkingTime::HOURLY_RATE,
                'Ilość nadgodzin z danego miesiąca' => $totalOvertimeHours,
                'Stawka nadgodzin' => WorkingTime::HOURLY_RATE * 2,
                'Suma po przeliczeniu' => $basicPay + $overtimePey
            ];
        } else {
            $result = [
                'userId' => $command->userId,
                'Zakres daty' => $command->date,
                'Suma po przeliczeniu' => $basicPay,
                'Stawka' => WorkingTime::HOURLY_RATE,
                'Ilość godzin danego dnia' => $totalBasicHour
            ];
        }

        return $result;
    }
}