<?php

declare(strict_types=1);

namespace App\Foo\Application\EventHandler;

use App\Foo\Domain\Event\FooCreated;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(
    bus: 'notificationBus',
    handles: FooCreated::class,
    method: 'handle'
)]
final class NotifyAboutFooCreatedEventHandler implements MessageConsumer
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function handle(Message $message): void
    {
        assert($message->payload() instanceof FooCreated);

        $this->logger->info('Notification sent!');
    }
}
