<?php

declare(strict_types=1);

namespace App\Foo\Domain\Event;

use App\Foo\Domain\FooId;
use App\Shared\Application\MessageMarker\MessageInterface;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class FooCreated implements MessageInterface, SerializablePayload
{
    public function __construct(
        private readonly FooId $id
    ) {
    }

    public function getId(): FooId
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
            FooId::fromString($payload['id']),
        );
    }
}
