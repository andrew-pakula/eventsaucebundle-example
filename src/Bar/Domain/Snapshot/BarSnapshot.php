<?php

declare(strict_types=1);

namespace App\Bar\Domain\Snapshot;

use DateTimeImmutable;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class BarSnapshot implements SerializablePayload
{
    public function __construct(private string $value, private DateTimeImmutable $updatedAt)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function toPayload(): array
    {
        return [
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'value' => $this->value,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            $payload['value'],
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $payload['updatedAt']),
        );
    }
}
