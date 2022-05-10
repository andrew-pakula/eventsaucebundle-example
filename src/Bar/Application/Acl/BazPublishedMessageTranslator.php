<?php

declare(strict_types=1);

namespace App\Bar\Application\Acl;

use Andreo\EventSauceBundle\Attribute\AsMessageTranslator;
use Andreo\EventSauceBundle\Attribute\ForInboundAcl;
use App\Bar\Application\EventHandler\BazPublishedEventHandler;
use App\Bar\Domain\BazValue;
use App\Baz\Domain\Event\BazPublished;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

#[ForInboundAcl(target: BazPublishedEventHandler::class)]
#[AsMessageTranslator]
final class BazPublishedMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        $event = $message->payload();
        assert($event instanceof BazPublished);

        return new Message(
            new BazValue($event->getValue()),
        );
    }
}
