<?php

declare(strict_types=1);

namespace App\Bar\Domain;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;

final class BarId implements AggregateRootId
{
    private function __construct(private readonly string $id)
    {
    }

    public function toString(): string
    {
        return $this->id;
    }

    /**
     * @return self&AggregateRootId
     */
    public static function fromString(string $aggregateRootId): static
    {
        return new self($aggregateRootId);
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
