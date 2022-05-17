<?php

declare(strict_types=1);

namespace App\Baz\Application\CommandHandler;

use App\Baz\Domain\Baz;
use App\Baz\Domain\Command\PublishBaz;
use App\Baz\Domain\Event\BazPublished;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\EventDispatcher;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'commandBus')]
final class PublishBazHandler
{
    public function __construct(
        #[Target('bazRepository')] private readonly AggregateRootRepository $repository,
        private readonly EventDispatcher $eventDispatcher
    ) {
    }

    public function __invoke(PublishBaz $command): void
    {
        /** @var Baz $baz */
        $baz = $this->repository->retrieve($command->getId());

        $this->eventDispatcher->dispatch(
            new BazPublished(
                $baz->aggregateRootId(),
                $baz->getValue(),
                1 === random_int(0, 1)
            ),
        );
    }
}
