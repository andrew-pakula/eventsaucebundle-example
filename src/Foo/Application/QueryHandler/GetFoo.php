<?php

declare(strict_types=1);

namespace App\Foo\Application\QueryHandler;

use App\Foo\Domain\FooId;

final class GetFoo
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
