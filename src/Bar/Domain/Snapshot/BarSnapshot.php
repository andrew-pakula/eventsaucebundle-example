<?php

declare(strict_types=1);

namespace App\Bar\Domain\Snapshot;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class BarSnapshot implements SerializablePayload
{
    public function __construct()
    {
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): static
    {
        return new self();
    }
}
