<?php

declare(strict_types=1);

namespace App\Bar\Api;

use App\Bar\Domain\BarId;
use App\Bar\Domain\Command\ChangeBar;
use App\Bar\Domain\Command\CreateBar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/bar')]
final class BarApiController
{
    #[Route(path: '/create', methods: Request::METHOD_POST)]
    public function create(MessageBusInterface $commandBus): Response
    {
        try {
            $cartId = BarId::create();
            $command = new CreateBar(
                $cartId
            );
            $commandBus->dispatch($command);
        } catch (Throwable $exception) {
            return new JsonResponse(sprintf('Error: %s', $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['id' => $cartId->toString()], Response::HTTP_CREATED);
    }

    #[Route(path: '/change', methods: Request::METHOD_PATCH)]
    public function change(Request $request, MessageBusInterface $commandBus): Response
    {
        try {
            $data = $request->toArray();
            $command = new ChangeBar(
                BarId::fromString($data['id']),
            );
            $commandBus->dispatch($command);
        } catch (Throwable $exception) {
            return new JsonResponse(sprintf('Error: %s', $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
