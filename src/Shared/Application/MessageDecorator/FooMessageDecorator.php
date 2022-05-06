<?php

declare(strict_types=1);

namespace App\Shared\Application\MessageDecorator;

use Andreo\EventSauceBundle\Attribute\AsMessageDecorator;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

#[AsMessageDecorator]
final class FooMessageDecorator implements MessageDecorator
{
    public function decorate(Message $message): Message
    {
        return $message->withHeader('__foo_decorator', bin2hex(random_bytes(10)));
    }
}
