<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    /**
     * @var Collection<int, WorkingTime>
     */
    #[ORM\OneToMany(targetEntity: WorkingTime::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $workingTimes;

    public function __construct()
    {
        $this->workingTimes = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, WorkingTime>
     */
    public function getWorkingTimes(): Collection
    {
        return $this->workingTimes;
    }

    public function addWorkingTime(WorkingTime $workingTime): static
    {
        if (!$this->workingTimes->contains($workingTime)) {
            $this->workingTimes->add($workingTime);
            $workingTime->setUser($this);
        }

        return $this;
    }

    public function removeWorkingTime(WorkingTime $workingTime): static
    {
        if ($this->workingTimes->removeElement($workingTime)) {
            // set the owning side to null (unless already changed)
            if ($workingTime->getUser() === $this) {
                $workingTime->setUser(null);
            }
        }

        return $this;
    }
}
