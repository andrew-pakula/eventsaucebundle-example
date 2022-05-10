<?php

declare(strict_types=1);

namespace App\Baz\Domain\Command;

use App\Baz\Domain\BazId;
use App\Baz\Domain\FooValue;
use App\Shared\Application\MessageMarker\NormalPriorityMessageInterface;

final class CreateBaz implements NormalPriorityMessageInterface
{
    public function __construct(
        private readonly BazId $id,
        private readonly FooValue $value
    ) {
    }

    public function getId(): BazId
    {
        return $this->id;
    }

    public function getValue(): FooValue
    {
        return $this->value;
    }
}
