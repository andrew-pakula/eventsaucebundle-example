<?php

declare(strict_types=1);

namespace App\Foo\Api;

use App\Foo\Domain\Command\ChangeFoo;
use App\Foo\Domain\Command\CreateFoo;
use App\Foo\Domain\FooId;
use App\Foo\Domain\ProductCode;
use App\Foo\Domain\ProductName;
use App\Foo\Domain\ProductPrice;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/foo')]
final class FooApiController
{
    #[Route(path: '/create', methods: Request::METHOD_POST)]
    public function create(Request $request, MessageBusInterface $commandBus): Response
    {
        try {
            $data = $request->toArray();
            $productId = FooId::create();
            $command = new CreateFoo(
                $productId,
                new ProductCode($data['code']),
                new ProductName($data['name']),
                ProductPrice::fromInt($data['price'])
            );
            $commandBus->dispatch($command);
        } catch (Throwable $exception) {
            return new JsonResponse(sprintf('Error: %s', $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['id' => $productId->toString()], Response::HTTP_CREATED);
    }

    #[Route(path: '/change', methods: Request::METHOD_PATCH)]
    public function rename(Request $request, MessageBusInterface $commandBus): Response
    {
        try {
            $data = $request->toArray();
            $command = new ChangeFoo(
                FooId::fromString($data['id']),
                new ProductName($data['name']),
            );
            $commandBus->dispatch($command);
        } catch (Throwable $exception) {
            return new JsonResponse(sprintf('Error: %s', $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
