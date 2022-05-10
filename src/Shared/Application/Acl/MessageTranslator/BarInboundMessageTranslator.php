<?php

declare(strict_types=1);

namespace App\Shared\Application\Acl\MessageTranslator;

use Andreo\EventSauceBundle\Attribute\AsMessageTranslator;
use Andreo\EventSauceBundle\Attribute\ForInboundAcl;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

#[AsMessageTranslator]
#[ForInboundAcl]
final class BarInboundMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        return $message->withHeader('__bar_inbound_header', bin2hex(random_bytes(10)));
    }
}
