<?php

declare(strict_types=1);

namespace App\Bar\Application\Upcaster;

use Andreo\EventSauce\Upcasting\Event;
use Andreo\EventSauce\Upcasting\MessageUpcaster;
use Andreo\EventSauceBundle\Attribute\AsUpcaster;
use App\Bar\Domain\Event\ItemFromCartRemoved;
use App\Bar\Domain\Event\ItemFromCartRemovedV2;
use EventSauce\EventSourcing\Message;

#[AsUpcaster(aggregate: 'cart', version: 2)]
final class ItemFromCartRemovedUpcaster implements MessageUpcaster
{
    #[Event(ItemFromCartRemoved::class)]
    public function upcast(Message $message): Message
    {
        $deprecatedEvent = $message->payload();
        assert($deprecatedEvent instanceof ItemFromCartRemoved);

        $event = new ItemFromCartRemovedV2(
            $deprecatedEvent->getCartId(),
            $deprecatedEvent->getCartItemProduct(),
            $deprecatedEvent->getRemovedAt(),
            'unknown'
        );

        return new Message($event);
    }
}
