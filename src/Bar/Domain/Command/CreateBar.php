<?php

declare(strict_types=1);

namespace App\Bar\Domain\Command;

use App\Bar\Domain\BarId;
use App\Shared\Application\MessageMarker\NormalPriorityMessageInterface;

final class CreateBar implements NormalPriorityMessageInterface
{
    public function __construct(private readonly BarId $id)
    {
    }

    public function getId(): BarId
    {
        return $this->id;
    }
}
