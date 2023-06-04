<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\Query\Query;
use App\Shared\Application\Bus\Query\QueryBus;
use App\Shared\Application\Bus\Query\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessengerQueryBus implements QueryBus
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function ask(Query $query): Response
    {
        try {
            $envelope = $this->messageBus->dispatch($query);
            /** @var HandledStamp $handledStamps */
            $handledStamps = $envelope->last(HandledStamp::class);
        } catch (HandlerFailedException $exception) {
            throw current($exception->getNestedExceptions());
        }

        return $handledStamps->getResult();
    }
}
