<?php

declare(strict_types=1);

namespace App\Bar\Domain\Event;

use App\Bar\Domain\BarId;
use App\Shared\Application\MessageMarker\MessageInterface;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class BarCreated implements MessageInterface, SerializablePayload
{
    public function __construct(private readonly BarId $id)
    {
    }

    public function getId(): BarId
    {
        return $this->id;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            BarId::fromString($payload['id']),
        );
    }
}
