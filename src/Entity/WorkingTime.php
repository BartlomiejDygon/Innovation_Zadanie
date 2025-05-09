<?php

namespace App\Entity;

use App\Repository\WorkingTimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: WorkingTimeRepository::class)]
class WorkingTime
{
    public const MAX_HOURS_PER_SHIFT = 12;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    #[ORM\ManyToOne(inversedBy: 'workingTimes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTime $startingWork = null;

    #[ORM\Column]
    private ?\DateTime $endingWork = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startDate = null;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStartingWork(): ?\DateTime
    {
        return $this->startingWork;
    }

    public function setStartingWork(\DateTime $startingWork): static
    {
        $this->startingWork = $startingWork;

        return $this;
    }

    public function getEndingWork(): ?\DateTime
    {
        return $this->endingWork;
    }

    public function setEndingWork(\DateTime $endingWork): static
    {
        $this->endingWork = $endingWork;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }
}
