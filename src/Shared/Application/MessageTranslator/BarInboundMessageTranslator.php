<?php

declare(strict_types=1);

namespace App\Shared\Application\MessageTranslator;

use Andreo\EventSauceBundle\Attribute\AclInboundTarget;
use Andreo\EventSauceBundle\Attribute\AsMessageTranslator;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

#[AsMessageTranslator]
#[AclInboundTarget]
final class BarInboundMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        return $message->withHeader('__bar_inbound_header', bin2hex(random_bytes(10)));
    }
}
