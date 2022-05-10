<?php

declare(strict_types=1);

namespace App\Bar\Domain\Event;

use App\Bar\Domain\BarId;
use App\Bar\Domain\BazValue;
use App\Shared\Application\MessageMarker\MessageInterface;
use DateTimeImmutable;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class BarFromBazCreated implements MessageInterface, SerializablePayload
{
    public function __construct(
        private readonly BarId $id,
        private readonly DateTimeImmutable $updatedAt,
        private readonly BazValue $bazValue
    ) {
    }

    public function getId(): BarId
    {
        return $this->id;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getBazValue(): BazValue
    {
        return $this->bazValue;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'bazValue' => $this->bazValue->getValue(),
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            BarId::fromString($payload['id']),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $payload['updatedAt']),
            new BazValue($payload['bazValue'])
        );
    }
}
