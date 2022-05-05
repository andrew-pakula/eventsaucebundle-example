<?php

declare(strict_types=1);

namespace App\Bar\Domain;

use Andreo\EventSauce\Aggregate\AggregateRootBehaviourWithAppliesByAttribute;
use Andreo\EventSauce\Aggregate\EventSourcingHandler;
use Andreo\EventSauce\Snapshotting\AggregateRootWithVersionedSnapshotting;
use Andreo\EventSauce\Snapshotting\VersionedSnapshottingBehaviour;
use App\Bar\Domain\Command\ChangeBar;
use App\Bar\Domain\Command\CreateBar;
use App\Bar\Domain\Event\BarChanged;
use App\Bar\Domain\Event\BarCreated;
use App\Bar\Domain\Snapshot\BarSnapshot;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;

final class Bar implements AggregateRootWithVersionedSnapshotting
{
    use AggregateRootBehaviourWithAppliesByAttribute;
    use VersionedSnapshottingBehaviour;

    public static function create(CreateBar $command, Clock $clock): self
    {
        $bar = new static($command->getId());
        $bar->recordThat(new BarCreated($command->getId()));

        return $bar;
    }

    #[EventSourcingHandler]
    public function onCreated(BarCreated $event): void
    {
    }

    public function change(ChangeBar $command, Clock $clock): void
    {
        $this->recordThat(new BarChanged($command->getId()));
    }

    #[EventSourcingHandler]
    public function onChanged(BarChanged $event): void
    {
    }

    public static function getSnapshotVersion(): int|string
    {
        return 1;
    }

    protected function createSnapshotState(): BarSnapshot
    {
        return new BarSnapshot();
    }

    protected static function reconstituteFromSnapshotState(AggregateRootId $id, $state): AggregateRootWithSnapshotting
    {
        assert($state instanceof BarSnapshot);

        return new static($id);
    }
}
