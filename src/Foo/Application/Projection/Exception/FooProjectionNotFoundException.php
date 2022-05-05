<?php

declare(strict_types=1);

namespace App\Foo\Application\Projection\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

final class FooProjectionNotFoundException extends Exception
{
    public static function create(UuidInterface $uuid): self
    {
        return new self(sprintf('Foo projection for id=%s not found.', $uuid));
    }
}
