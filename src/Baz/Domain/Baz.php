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

    private FooValue $fooValue;

    private string $value;

    public static function create(CreateBaz $command, Clock $clock): self
    {
        $cart = new static($command->getId());

        $cart->recordThat(new BazCreated(
            $command->getId(),
            $clock->now(),
            $command->getValue(),
            bin2hex(random_bytes(10)),
        ));

        return $cart;
    }

    #[EventSourcingHandler]
    public function onCreated(BazCreated $event): void
    {
        $this->updatedAt = $event->getUpdatedAt();
        $this->fooValue = $event->getFooValue();
        $this->value = $event->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
