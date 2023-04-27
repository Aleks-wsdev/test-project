<?php

declare(strict_types=1);

namespace App\Model;

class ErrorValidationDetails
{
    private array $violations = [];

    public function addViolation(string $field, string $message): void
    {
        $this->violations[] = new ErrorValidationDetailsItem($field, $message);
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}
