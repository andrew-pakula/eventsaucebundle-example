<?php

declare(strict_types=1);

namespace App\Shared\Application\Acl\MessageTranslator;

use Andreo\EventSauceBundle\Attribute\AsMessageTranslator;
use Andreo\EventSauceBundle\Attribute\ForOutboundAcl;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

#[AsMessageTranslator]
#[ForOutboundAcl]
final class FooOutboundMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        return $message->withHeader('__foo_outbound_header', bin2hex(random_bytes(10)));
    }
}
