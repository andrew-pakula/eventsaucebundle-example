<?php

declare(strict_types=1);

namespace App\Shared\Application\MessageTranslator;

use Andreo\EventSauceBundle\Attribute\AclOutboundTarget;
use Andreo\EventSauceBundle\Attribute\AsMessageTranslator;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

#[AsMessageTranslator]
#[AclOutboundTarget]
final class FooOutboundMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        return $message->withHeader('__foo_outbound_header', bin2hex(random_bytes(10)));
    }
}
