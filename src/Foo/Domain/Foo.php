<?php

declare(strict_types=1);

namespace App\Foo\Domain;

use Andreo\EventSauce\Aggregate\AggregateRootBehaviourWithAppliesByAttribute;
use Andreo\EventSauce\Aggregate\EventSourcingHandler;
use App\Foo\Domain\Command\ChangeFoo;
use App\Foo\Domain\Command\CreateFoo;
use App\Foo\Domain\Event\FooChanged;
use App\Foo\Domain\Event\FooCreated;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRoot;

final class Foo implements AggregateRoot
{
    use AggregateRootBehaviourWithAppliesByAttribute;

    public static function create(CreateFoo $command, Clock $clock): self
    {
        $cart = new static($command->getId());

        $cart->recordThat(new FooCreated(
            $command->getId(),
        ));

        return $cart;
    }

    #[EventSourcingHandler]
    public function onCreated(FooCreated $event): void
    {
    }

    public function change(ChangeFoo $command, Clock $clock): void
    {
        $this->recordThat(new FooChanged(
            $command->getId(),
        ));
    }

    #[EventSourcingHandler]
    public function onChanged(FooChanged $event): void
    {
    }
}
