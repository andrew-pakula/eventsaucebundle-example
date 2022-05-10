<?php

declare(strict_types=1);

namespace App\Shared\Application\Acl\MessageTranslator;

use Andreo\EventSauceBundle\Attribute\AsMessageTranslator;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

#[AsMessageTranslator]
final class BazMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        return $message->withHeader('__baz_acl_header', bin2hex(random_bytes(10)));
    }
}
