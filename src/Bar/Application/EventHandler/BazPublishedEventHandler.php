<?php

declare(strict_types=1);

namespace App\Bar\Application\EventHandler;

use Andreo\EventSauceBundle\Attribute\InboundAcl;
use App\Bar\Domain\BarId;
use App\Bar\Domain\BazValue;
use App\Bar\Domain\Command\CreateBarFromBaz;
use App\Baz\Domain\Event\BazPublished;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[InboundAcl]
#[AsMessageHandler(
    bus: 'eventBus',
    handles: BazPublished::class,
    method: 'handle'
)]
final class BazPublishedEventHandler implements MessageConsumer
{
    public function __construct(#[Target('commandBus')] private MessageBusInterface $commandBus)
    {
    }

    public function handle(Message $message): void
    {
        $bazValue = $message->payload();
        assert($bazValue instanceof BazValue);

        $this->commandBus->dispatch(new CreateBarFromBaz(
            BarId::create(),
            $bazValue
        ));
    }
}
