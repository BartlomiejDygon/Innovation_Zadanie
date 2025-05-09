<?php
namespace App\Application\Command\User;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserCommand
{
    #[Assert\NotBlank(message: 'Imię jest wymagane')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Imię musi mieć co najmniej {{ limit }} znaki.',
        maxMessage: 'Imię nie może mieć więcej niż {{ limit }} znaków.'
    )]
    public ?string $firstName;

    #[Assert\NotBlank(message: 'Nazwisko jest wymagane')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Nazwisko musi mieć co najmniej {{ limit }} znaki.',
        maxMessage: 'Nazwisko nie może mieć więcej niż {{ limit }} znaków.'
    )]
    public ?string $lastName;

    public function __construct(?string $firstName, ?string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}