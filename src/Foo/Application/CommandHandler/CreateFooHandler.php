<?php

declare(strict_types=1);

namespace App\Foo\Application\CommandHandler;

use App\Foo\Domain\Command\CreateFoo;
use App\Foo\Domain\Foo;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'commandBus')]
final class CreateFooHandler
{
    public function __construct(
        #[Target('fooRepository')] private readonly AggregateRootRepository $repository,
        private readonly Clock $clock
    ) {
    }

    public function __invoke(CreateFoo $command): void
    {
        $foo = Foo::create($command, $this->clock);
        $this->repository->persist($foo);
    }
}
