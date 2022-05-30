<?php

declare(strict_types=1);

namespace App\Baz\Infrastructure\Repository;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: 'bazRepository')]
final class BazOverwritingExampleRepository implements AggregateRootRepository
{
    public function __construct(private readonly AggregateRootRepository $originBarRepository)
    {
    }

    public function retrieve(AggregateRootId $aggregateRootId): object
    {
        return $this->originBarRepository->retrieve($aggregateRootId);
    }

    public function persist(object $aggregateRoot): void
    {
        $this->originBarRepository->persist($aggregateRoot);
    }

    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events): void
    {
        $this->originBarRepository->persist($aggregateRootId);
    }
}
