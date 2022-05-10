<?php

declare(strict_types=1);

namespace App\Bar\Domain;

use Andreo\EventSauce\Aggregate\AggregateRootBehaviourWithAppliesByAttribute;
use Andreo\EventSauce\Aggregate\EventSourcingHandler;
use Andreo\EventSauce\Snapshotting\AggregateRootWithVersionedSnapshotting;
use Andreo\EventSauce\Snapshotting\VersionedSnapshottingBehaviour;
use App\Bar\Domain\Command\ChangeBar;
use App\Bar\Domain\Command\CreateBar;
use App\Bar\Domain\Command\CreateBarFromBaz;
use App\Bar\Domain\Event\BarChanged;
use App\Bar\Domain\Event\BarCreated;
use App\Bar\Domain\Event\BarFromBazCreated;
use App\Bar\Domain\Snapshot\BarSnapshot;
use DateTimeImmutable;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;

final class Bar implements AggregateRootWithVersionedSnapshotting
{
    use AggregateRootBehaviourWithAppliesByAttribute;
    use VersionedSnapshottingBehaviour;

    private DateTimeImmutable $updatedAt;

    private string $value;

    private BazValue $bazValue;

    public static function create(CreateBar $command, Clock $clock): self
    {
        $bar = new static($command->getId());
        $bar->recordThat(new BarCreated(
            $command->getId(),
            $clock->now()
        ));

        return $bar;
    }

    #[EventSourcingHandler]
    public function onCreated(BarCreated $event): void
    {
        $this->updatedAt = $event->getUpdatedAt();
    }

    public static function createFromBaz(CreateBarFromBaz $command, Clock $clock): self
    {
        $bar = new static($command->getId());
        $bar->recordThat(new BarFromBazCreated(
            $command->getId(),
            $clock->now(),
            $command->getBazValue()
        ));

        return $bar;
    }

    #[EventSourcingHandler]
    public function onFromBazCreated(BarFromBazCreated $event): void
    {
        $this->updatedAt = $event->getUpdatedAt();
        $this->bazValue = $event->getBazValue();
    }

    public function change(ChangeBar $command, Clock $clock): void
    {
        $this->recordThat(new BarChanged(
            $command->getId(),
            $clock->now(),
            bin2hex(random_bytes(10)),
        ));
    }

    #[EventSourcingHandler]
    public function onChanged(BarChanged $event): void
    {
        $this->updatedAt = $event->getUpdatedAt();
        $this->value = $event->getValue();
    }

    public static function getSnapshotVersion(): int|string
    {
        return 1;
    }

    protected function createSnapshotState(): BarSnapshot
    {
        return new BarSnapshot($this->value, $this->updatedAt);
    }

    protected static function reconstituteFromSnapshotState(AggregateRootId $id, $state): AggregateRootWithSnapshotting
    {
        assert($state instanceof BarSnapshot);

        return new static($id);
    }
}
