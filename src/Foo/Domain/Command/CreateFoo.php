<?php

declare(strict_types=1);

namespace App\Foo\Domain\Command;

use App\Foo\Domain\FooId;
use App\Foo\Domain\ProductCode;
use App\Foo\Domain\ProductName;
use App\Foo\Domain\ProductPrice;
use App\Shared\Application\MessageMarker\NormalPriorityMessageInterface;

final class CreateFoo implements NormalPriorityMessageInterface
{
    public function __construct(
        private readonly FooId $id,
        private readonly ProductCode $code,
        private readonly ProductPrice $price
    ) {
    }

    public function getId(): FooId
    {
        return $this->id;
    }

    public function getCode(): ProductCode
    {
        return $this->code;
    }

    public function getName(): ProductName
    {
        return $this->name;
    }

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }
}
