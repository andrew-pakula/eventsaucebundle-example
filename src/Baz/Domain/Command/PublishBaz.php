<?php

declare(strict_types=1);

namespace App\Baz\Domain\Command;

use App\Baz\Domain\BazId;
use App\Shared\Application\MessageMarker\NormalPriorityMessageInterface;

final class PublishBaz implements NormalPriorityMessageInterface
{
    public function __construct(
        private readonly BazId $id,
    ) {
    }

    public function getId(): BazId
    {
        return $this->id;
    }
}
