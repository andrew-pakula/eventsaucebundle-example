<?php

declare(strict_types=1);

namespace App\Baz\Api;

use App\Baz\Domain\BazId;
use App\Baz\Domain\Command\PublishBaz;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/baz')]
final class BazApiController
{
    #[Route(path: '/publish', methods: Request::METHOD_POST)]
    public function publish(Request $request, MessageBusInterface $commandBus): Response
    {
        try {
            $data = $request->toArray();
            $command = new PublishBaz(
                BazId::fromString($data['id']),
            );
            $commandBus->dispatch($command);
        } catch (Throwable $exception) {
            return new JsonResponse(sprintf('Error: %s', $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
