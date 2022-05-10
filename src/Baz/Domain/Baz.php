<?php

declare(strict_types=1);

namespace App\Baz\Domain;

use Andreo\EventSauce\Aggregate\AggregateRootBehaviourWithAppliesByAttribute;
use Andreo\EventSauce\Aggregate\EventSourcingHandler;
use App\Baz\Domain\Command\CreateBaz;
use App\Baz\Domain\Event\BazCreated;
use DateTimeImmutable;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRoot;

final class Baz implements AggregateRoot
{
    use AggregateRootBehaviourWithAppliesByAttribute;

    private DateTimeImmutable $updatedAt;

    private FooValue $value;

    public static function create(CreateBaz $command, Clock $clock): self
    {
        $cart = new static($command->getId());

        $cart->recordThat(new BazCreated(
            $command->getId(),
            $clock->now(),
            $command->getValue(),
        ));

        return $cart;
    }

    #[EventSourcingHandler]
    public function onCreated(BazCreated $event): void
    {
        $this->updatedAt = $event->getUpdatedAt();
        $this->value = $event->getValue();
    }
}
