<?php

declare(strict_types=1);

namespace App\Baz\Application\CommandHandler;

use App\Baz\Domain\Baz;
use App\Baz\Domain\Command\CreateBaz;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'commandBus')]
final class CreateBazHandler
{
    public function __construct(
        #[Target('bazRepository')] private readonly AggregateRootRepository $repository,
        private readonly Clock $clock
    ) {
    }

    public function __invoke(CreateBaz $command): void
    {
        $foo = Baz::create($command, $this->clock);
        $this->repository->persist($foo);
    }
}
