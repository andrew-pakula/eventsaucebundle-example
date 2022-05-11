<?php

declare(strict_types=1);

namespace App\Baz\Application\EventHandler;

use Andreo\EventSauceBundle\Attribute\InboundAcl;
use App\Baz\Domain\BazId;
use App\Baz\Domain\Command\CreateBaz;
use App\Baz\Domain\FooValue;
use App\Foo\Domain\Event\FooChangedV2 as FooChanged;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[InboundAcl]
#[AsMessageHandler(
    bus: 'eventBus',
    handles: FooChanged::class,
    method: 'handle'
)]
final class FooChangedEventHandler implements MessageConsumer
{
    public function __construct(#[Target('commandBus')] private MessageBusInterface $commandBus)
    {
    }

    public function handle(Message $message): void
    {
        $fooValue = $message->payload();
        assert($fooValue instanceof FooValue);

        $this->commandBus->dispatch(
            new CreateBaz(BazId::create(), $fooValue)
        );
    }
}
