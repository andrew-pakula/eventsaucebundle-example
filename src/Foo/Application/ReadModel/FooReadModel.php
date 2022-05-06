<?php

declare(strict_types=1);

namespace App\Foo\Application\ReadModel;

use JsonSerializable;

final class FooReadModel implements JsonSerializable
{
    public function __construct(
        private readonly string $id,
        private readonly ?string $value,
        private readonly array $headers
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'headers' => $this->headers,
        ];
    }
}
