<?php

declare(strict_types=1);

namespace App\Foo\Api;

use App\Foo\Application\QueryHandler\GetFoo;
use App\Foo\Application\ReadModel\FooReadModel;
use App\Foo\Domain\Command\ChangeFoo;
use App\Foo\Domain\Command\CreateFoo;
use App\Foo\Domain\FooId;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/foo')]
final class FooApiController
{
    use HandleTrait;

    public function __construct(#[Target('queryBus')] private MessageBusInterface $queryBus)
    {
        $this->messageBus = $this->queryBus;
    }

    #[Route(path: '/create', methods: Request::METHOD_POST)]
    public function create(Request $request, MessageBusInterface $commandBus): Response
    {
        try {
            $productId = FooId::create();
            $command = new CreateFoo(
                $productId,
            );
            $commandBus->dispatch($command);
        } catch (Throwable $exception) {
            return new JsonResponse(sprintf('Error: %s', $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['id' => $productId->toString()], Response::HTTP_CREATED);
    }

    #[Route(path: '/change', methods: Request::METHOD_PATCH)]
    public function change(Request $request, MessageBusInterface $commandBus): Response
    {
        try {
            $data = $request->toArray();
            $command = new ChangeFoo(
                FooId::fromString($data['id']),
            );
            $commandBus->dispatch($command);
        } catch (Throwable $exception) {
            return new JsonResponse(sprintf('Error: %s', $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/get/{id}', methods: Request::METHOD_GET)]
    public function get(string $id): Response
    {
        try {
            $query = new GetFoo(
                FooId::fromString($id),
            );
            /** @var FooReadModel $readModel */
            $readModel = $this->handle($query);

            return new JsonResponse($readModel, status: Response::HTTP_OK);
        } catch (Throwable $exception) {
            return new JsonResponse(sprintf('Error: %s', $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
