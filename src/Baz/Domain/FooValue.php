<?php

declare(strict_types=1);

namespace App\Baz\Domain;

final class FooValue
{
    public function __construct(private string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
