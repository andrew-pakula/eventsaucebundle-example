<?php

declare(strict_types=1);

namespace App\Bar\Application\CommandHandler;

use App\Bar\Domain\Bar;
use App\Bar\Domain\Command\CreateBar;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\Snapshotting\AggregateRootRepositoryWithSnapshotting;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'commandBus')]
final class CreateBarHandler
{
    public function __construct(
        #[Target('barRepository')] private readonly AggregateRootRepositoryWithSnapshotting $repository,
        private readonly Clock $clock
    ) {
    }

    public function __invoke(CreateBar $command): void
    {
        $bar = Bar::create($command, $this->clock);
        $this->repository->persist($bar);
    }
}
