<?php

declare(strict_types=1);

namespace App\Foo\Domain;

use Andreo\EventSauce\Aggregate\AggregateRootBehaviourWithAppliesByAttribute;
use Andreo\EventSauce\Aggregate\EventSourcingHandler;
use App\Foo\Domain\Command\ChangeFoo;
use App\Foo\Domain\Command\CreateFoo;
use App\Foo\Domain\Event\FooChangedV2 as FooChanged;
use App\Foo\Domain\Event\FooCreated;
use DateTimeImmutable;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRoot;

final class Foo implements AggregateRoot
{
    use AggregateRootBehaviourWithAppliesByAttribute;

    private DateTimeImmutable $updatedAt;

    private string $value;

    public static function create(CreateFoo $command, Clock $clock): self
    {
        $cart = new static($command->getId());

        $cart->recordThat(new FooCreated(
            $command->getId(),
            $clock->now()
        ));

        return $cart;
    }

    #[EventSourcingHandler]
    public function onCreated(FooCreated $event): void
    {
        $this->updatedAt = $event->getUpdatedAt();
    }

    public function change(ChangeFoo $command, Clock $clock): void
    {
        $this->recordThat(new FooChanged(
            $command->getId(),
            $clock->now(),
            bin2hex(random_bytes(10)),
//            bin2hex(random_bytes(20))
        ));
    }

    #[EventSourcingHandler]
    public function onChanged(FooChanged $event): void
    {
        $this->updatedAt = $event->getUpdatedAt();
        $this->value = $event->getValue();
    }
}
