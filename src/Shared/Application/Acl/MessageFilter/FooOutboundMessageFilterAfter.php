<?php

declare(strict_types=1);

namespace App\Shared\Application\Acl\MessageFilter;

use Andreo\EventSauceBundle\Attribute\AsMessageFilter;
use Andreo\EventSauceBundle\Attribute\ForOutboundAcl;
use Andreo\EventSauceBundle\Enum\FilterPosition;
use App\Shared\Application\MessageMarker\MessageInterface;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilter;
use EventSauce\EventSourcing\Message;

#[ForOutboundAcl]
#[AsMessageFilter(FilterPosition::AFTER)]
final class FooOutboundMessageFilterAfter implements MessageFilter
{
    public function allows(Message $message): bool
    {
        return $message->payload() instanceof MessageInterface;
    }
}
