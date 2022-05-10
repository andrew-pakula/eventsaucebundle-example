<?php

declare(strict_types=1);

namespace App\Bar\Domain;

final class BazValue
{
    public function __construct(private string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
