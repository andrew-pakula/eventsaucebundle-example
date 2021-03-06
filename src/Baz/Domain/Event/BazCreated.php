<?php

declare(strict_types=1);

namespace App\Baz\Domain\Event;

use App\Baz\Domain\BazId;
use App\Baz\Domain\FooValue;
use App\Shared\Application\MessageMarker\MessageInterface;
use DateTimeImmutable;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class BazCreated implements MessageInterface, SerializablePayload
{
    public function __construct(
        private readonly BazId $id,
        private readonly DateTimeImmutable $updatedAt,
        private readonly FooValue $fooValue,
        private readonly string $value
    ) {
    }

    public function getId(): BazId
    {
        return $this->id;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getFooValue(): FooValue
    {
        return $this->fooValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'fooValue' => $this->fooValue->getValue(),
            'value' => $this->value,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            BazId::fromString($payload['id']),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $payload['updatedAt']),
            new FooValue($payload['fooValue']),
            $payload['value']
        );
    }
}
