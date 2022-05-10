<?php

declare(strict_types=1);

namespace App\Baz\Application\Acl;

use Andreo\EventSauceBundle\Attribute\AsMessageTranslator;
use Andreo\EventSauceBundle\Attribute\ForInboundAcl;
use App\Baz\Application\EventHandler\FooChangedEventHandler;
use App\Baz\Domain\FooValue;
use App\Foo\Domain\Event\FooChangedV2 as FooChanged;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

#[ForInboundAcl(target: FooChangedEventHandler::class)]
#[AsMessageTranslator]
final class FooChangedMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        $event = $message->payload();
        assert($event instanceof FooChanged);

        return new Message(
            new FooValue($event->getValue()),
        );
    }
}
