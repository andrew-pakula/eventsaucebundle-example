<?php

declare(strict_types=1);

namespace App\Bar\Application\Acl;

use Andreo\EventSauceBundle\Attribute\AsMessageFilter;
use Andreo\EventSauceBundle\Attribute\ForInboundAcl;
use Andreo\EventSauceBundle\Enum\FilterPosition;
use App\Bar\Application\EventHandler\BazPublishedEventHandler;
use App\Baz\Domain\Event\BazPublished;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilter;
use EventSauce\EventSourcing\Message;

#[AsMessageFilter(FilterPosition::BEFORE)]
#[ForInboundAcl(BazPublishedEventHandler::class)]
final class BazPublishedMessageFilter implements MessageFilter
{
    public function allows(Message $message): bool
    {
        $event = $message->payload();
        assert($event instanceof BazPublished);

        return $event->isPublic();
    }
}
