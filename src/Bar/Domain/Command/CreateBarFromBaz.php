<?php

declare(strict_types=1);

namespace App\Bar\Domain\Command;

use App\Bar\Domain\BarId;
use App\Bar\Domain\BazValue;
use App\Shared\Application\MessageMarker\NormalPriorityMessageInterface;

final class CreateBarFromBaz implements NormalPriorityMessageInterface
{
    public function __construct(
        private readonly BarId $id,
        private readonly BazValue $bazValue
    ) {
    }

    public function getId(): BarId
    {
        return $this->id;
    }

    public function getBazValue(): BazValue
    {
        return $this->bazValue;
    }
}
