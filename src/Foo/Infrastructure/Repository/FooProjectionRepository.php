<?php

declare(strict_types=1);

namespace App\Foo\Infrastructure\Repository;

use App\Foo\Application\Projection\Entity\FooProjection;
use App\Foo\Application\Projection\Exception\FooProjectionNotFoundException;
use App\Foo\Application\Projection\FooProjectionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

final class FooProjectionRepository extends ServiceEntityRepository implements FooProjectionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, string $entityClass = FooProjection::class)
    {
        parent::__construct($registry, $entityClass);
    }

    public function getForFoo(UuidInterface $uuid): FooProjection
    {
        /** @var FooProjection|null $product */
        $product = $this->find($uuid);
        if (null === $product) {
            throw FooProjectionNotFoundException::create($uuid);
        }

        return $product;
    }

    public function add(FooProjection $fooProjection): void
    {
        $this->getEntityManager()->persist($fooProjection);
        $this->getEntityManager()->flush();
    }

    public function update(): void
    {
        $this->getEntityManager()->flush();
    }
}
