<?php

declare(strict_types=1);

namespace App\Foo\Application\CommandHandler;

use App\Foo\Domain\Command\ChangeFoo;
use App\Foo\Domain\Foo;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'commandBus')]
final class ChangeFooHandler
{
    public function __construct(
        #[Target('fooRepository')] private readonly AggregateRootRepository $repository,
        private readonly Clock $clock
    ) {
    }

    public function __invoke(ChangeFoo $command): void
    {
        /** @var Foo $foo */
        $foo = $this->repository->retrieve($command->getId());
        $foo->change($command, $this->clock);

        $this->repository->persist($foo);
    }
}
