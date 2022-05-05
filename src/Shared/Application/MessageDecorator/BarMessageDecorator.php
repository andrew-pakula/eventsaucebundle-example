<?php

declare(strict_types=1);

namespace App\Shared\Application\MessageDecorator;

use Andreo\EventSauceBundle\Attribute\AsMessageDecorator;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

#[AsMessageDecorator]
final class BarMessageDecorator implements MessageDecorator
{
    public function __construct()
    {
    }

    public function decorate(Message $message): Message
    {
        return $message->withHeader('__bar', uniqid('bar', true));
    }
}
