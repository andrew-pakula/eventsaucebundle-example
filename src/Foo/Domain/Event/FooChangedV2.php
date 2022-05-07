<?php

declare(strict_types=1);

namespace App\Foo\Domain\Event;

use App\Foo\Domain\FooId;
use App\Shared\Application\MessageMarker\MessageInterface;
use DateTimeImmutable;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class FooChangedV2 implements MessageInterface, SerializablePayload
{
    public function __construct(
        private readonly FooId $id,
        private readonly DateTimeImmutable $updatedAt,
        private string $value,
        private string $upcaster
    ) {
    }

    public function getId(): FooId
    {
        return $this->id;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getUpcaster(): string
    {
        return $this->upcaster;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'value' => $this->value,
            'upcaster' => $this->upcaster,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            FooId::fromString($payload['id']),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $payload['updatedAt']),
            $payload['value'],
            $payload['upcaster']
        );
    }
}
