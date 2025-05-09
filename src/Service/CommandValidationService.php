<?php
namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommandValidationService
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate($command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            throw new BadRequestHttpException(implode(', ', $messages));
        }
    }
}