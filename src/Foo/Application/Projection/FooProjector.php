<?php

declare(strict_types=1);

namespace App\Foo\Application\Projection;

use Andreo\EventSauceBundle\Attribute\Acl;
use App\Foo\Application\Projection\Entity\FooProjection;
use App\Foo\Domain\Event\FooChanged;
use App\Foo\Domain\Event\FooCreated;
use App\Foo\Infrastructure\Repository\FooProjectionRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

#[Acl]
final class FooProjector implements MessageConsumer, MessageSubscriberInterface
{
    public function __construct(private readonly FooProjectionRepository $productCatalogRepository)
    {
    }

    public function handle(Message $message): void
    {
        $event = $message->payload();
        if ($event instanceof FooCreated) {
            $this->onCreated($event, $message->headers());
        }
        if ($event instanceof FooChanged) {
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

        $productCatalog = FooProjection::create(
            Uuid::fromString($event->getId()->toString()),
            json_encode($headers, JSON_THROW_ON_ERROR)
        );

        $this->productCatalogRepository->add($productCatalog);
    }

    private function onChanged(FooChanged $event): void
    {
        $fooProjection = $this->productCatalogRepository->getForFoo(Uuid::fromString($event->getId()->toString()));
        $this->productCatalogRepository->update();
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
