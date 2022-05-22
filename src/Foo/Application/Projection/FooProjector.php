<?php

declare(strict_types=1);

namespace App\Foo\Application\Projection;

use App\Foo\Application\Projection\Entity\FooProjection;
use App\Foo\Domain\Event\FooChanged;
use App\Foo\Domain\Event\FooCreated;
use App\Foo\Infrastructure\Repository\FooProjectionRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class FooProjector implements MessageConsumer, MessageSubscriberInterface
{
    public function __construct(private readonly FooProjectionRepository $fooProjectionRepository)
    {
    }

    public function handle(Message $message): void
    {
        $event = $message->payload();
        if ($event instanceof FooCreated) {
            $this->onCreated($event, $message->headers());
        } elseif ($event instanceof FooChanged) {
            $this->onChanged($event);
        }
    }

    private function onCreated(FooCreated $event, array $headers): void
    {
        foreach ($headers as $key => $value) {
            if (!is_string($value)) {
                unset($headers[$key]);
            }
        }

        $fooProjection = FooProjection::create(
            Uuid::fromString($event->getId()->toString()),
            json_encode($headers, JSON_THROW_ON_ERROR)
        );

        $this->fooProjectionRepository->add($fooProjection);
    }

    private function onChanged(FooChanged $event): void
    {
        $fooProjection = $this->fooProjectionRepository->getForFoo(Uuid::fromString($event->getId()->toString()));
        $fooProjection->change($event->getValue());
        $this->fooProjectionRepository->update();
    }

    public static function getHandledMessages(): iterable
    {
        yield FooCreated::class => [
            'method' => 'handle',
            'bus' => 'eventBus',
        ];

        yield FooChanged::class => [
            'method' => 'handle',
            'bus' => 'eventBus',
        ];
    }
}
