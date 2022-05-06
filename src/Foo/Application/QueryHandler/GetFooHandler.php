<?php

declare(strict_types=1);

namespace App\Foo\Application\QueryHandler;

use App\Foo\Application\ReadModel\FooReadModel;
use App\Foo\Infrastructure\Repository\FooProjectionRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'queryBus')]
final class GetFooHandler
{
    public function __construct(private FooProjectionRepository $fooProjectionRepository)
    {
    }

    public function __invoke(GetFoo $query): FooReadModel
    {
        $fooProjection = $this->fooProjectionRepository->getForFoo(Uuid::fromString($query->getId()->toString()));

        return new FooReadModel(
            $fooProjection->getId()->toString(),
            $fooProjection->getValue(),
            json_decode($fooProjection->getHeaders(), true, flags: JSON_THROW_ON_ERROR)
        );
    }
}
