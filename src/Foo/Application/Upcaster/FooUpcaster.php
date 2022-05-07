<?php

declare(strict_types=1);

namespace App\Foo\Application\Upcaster;

use Andreo\EventSauce\Upcasting\Event;
use Andreo\EventSauce\Upcasting\MessageUpcaster;
use Andreo\EventSauceBundle\Attribute\AsUpcaster;
use App\Foo\Domain\Event\FooChanged;
use App\Foo\Domain\Event\FooChangedV2;
use EventSauce\EventSourcing\Message;

#[AsUpcaster(aggregate: 'foo', version: 2)]
final class FooUpcaster implements MessageUpcaster
{
    #[Event(FooChanged::class)]
    public function upcast(Message $message): Message
    {
        $event = $message->payload();
        assert($event instanceof FooChanged);

        $newEvent = new FooChangedV2(
            $event->getId(),
            $event->getUpdatedAt(),
            $event->getValue(),
            bin2hex(random_bytes(20))
        );

        return new Message($newEvent, $message->headers());
    }
}
