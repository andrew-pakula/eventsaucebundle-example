<?php

declare(strict_types=1);

namespace App\Bar\Application\CommandHandler;

use App\Bar\Domain\Bar;
use App\Bar\Domain\Command\CreateBarFromBaz;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\Snapshotting\AggregateRootRepositoryWithSnapshotting;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'commandBus')]
final class CreateBarFromBazHandler
{
    public function __construct(
        #[Target('barRepository')] private readonly AggregateRootRepositoryWithSnapshotting $repository,
        private readonly Clock $clock
    ) {
    }

    public function __invoke(CreateBarFromBaz $command): void
    {
        $bar = Bar::createFromBaz($command, $this->clock);
        $this->repository->persist($bar);
    }
}
