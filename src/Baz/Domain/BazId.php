<?php

declare(strict_types=1);

namespace App\Baz\Domain;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;

final class BazId implements AggregateRootId
{
    private function __construct(private string $id)
    {
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new self($aggregateRootId);
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
