<?php

declare(strict_types=1);

namespace App\Baz\Domain\Event;

use App\Baz\Domain\BazId;
use App\Shared\Application\MessageMarker\MessageInterface;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class BazPublished implements MessageInterface, SerializablePayload
{
    public function __construct(
        private readonly BazId $id,
        private readonly string $value,
        private readonly bool $public
    ) {
    }

    public function getId(): BazId
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'value' => $this->value,
            'public' => $this->public,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            BazId::fromString($payload['id']),
            $payload['value'],
            $payload['public']
        );
    }
}
