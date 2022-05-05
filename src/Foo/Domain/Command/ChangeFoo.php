<?php

declare(strict_types=1);

namespace App\Foo\Domain\Command;

use App\Foo\Domain\FooId;
use App\Shared\Application\MessageMarker\NormalPriorityMessageInterface;

final class ChangeFoo implements NormalPriorityMessageInterface
{
    public function __construct(
        private readonly FooId $id,
    ) {
    }

    public function getId(): FooId
    {
        return $this->id;
    }
}
