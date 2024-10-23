<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\Query\CacheQueryBus;
use App\Shared\Application\Bus\Query\Query;
use App\Shared\Application\Bus\Query\Response;
use App\Shared\Infrastructure\Service\CacheKeyCreator;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class MessengerCacheQueryBus implements CacheQueryBus
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private CacheItemPoolInterface $cache,
        private CacheKeyCreator $cacheKeyCreator
    ) {
    }

    public function ask(Query $query, int $ttl = self::TTL_HOUR): Response
    {
        $cacheItem = $this->cache->getItem($this->cacheKeyCreator->execute($query));

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        try {
            $envelope = $this->messageBus->dispatch($query);
            /** @var HandledStamp $handledStamps */
            $handledStamps = $envelope->last(HandledStamp::class);
        } catch (HandlerFailedException $exception) {
            throw current($exception->getWrappedExceptions());
        }

        $result = $handledStamps->getResult();

        $cacheItem->set($result);
        $cacheItem->expiresAfter($ttl);
        $this->cache->save($cacheItem);

        return $result;
    }
}
