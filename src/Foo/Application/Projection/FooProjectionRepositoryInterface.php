<?php

declare(strict_types=1);

namespace App\Foo\Application\Projection;

use App\Foo\Application\Projection\Entity\FooProjection;
use Ramsey\Uuid\UuidInterface;

interface FooProjectionRepositoryInterface
{
    public function getForFoo(UuidInterface $uuid): FooProjection;

    public function add(FooProjection $productCatalog): void;

    public function update(): void;
}
