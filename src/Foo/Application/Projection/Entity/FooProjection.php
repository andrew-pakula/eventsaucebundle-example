<?php

declare(strict_types=1);

namespace App\Foo\Application\Projection\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\UuidInterface;

#[Entity]
class FooProjection
{
    #[Id]
    #[Column(type: 'uuid_binary', unique: true)]
    #[GeneratedValue(strategy: 'NONE')]
    private UuidInterface $id;

    #[Column(type: 'text')]
    private string $headers;

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function create(UuidInterface $id, string $headers): self
    {
        $productCatalog = new self($id);

        $productCatalog->headers = $headers;

        return $productCatalog;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getHeaders(): string
    {
        return $this->headers;
    }
}
