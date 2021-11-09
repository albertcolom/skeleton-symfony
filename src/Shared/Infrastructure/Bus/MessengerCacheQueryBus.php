<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\Query\CacheQueryBus;
use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Domain\ValueObject\CacheKey;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerCacheQueryBus implements CacheQueryBus
{
    public function __construct(private MessageBusInterface $messageBus, private CacheItemPoolInterface $cache)
    {
    }

    public function ask(Query $query, int $ttl = self::TTL_HOUR): Response
    {
        //dev-FindFooQuery-c7fa107554ebbe4dcca7bb45b1d188ef
        //test_041121162529_23288-FindFooQuery-f970918085d8eaa15c03c39207077302
        //dev-FindFooQuery-f3a102d81b436ea39327bfe9a8391daa

        $cacheItem = $this->cache->getItem(CacheKey::fromObject($query)->value());

        $hola = $this->cache->getItem('dev*');

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        try {
            $envelope = $this->messageBus->dispatch($query);
            /** @var HandledStamp $handledStamps */
            $handledStamps = $envelope->last(HandledStamp::class);
        } catch (HandlerFailedException $exception) {
            throw current($exception->getNestedExceptions());
        }

        $result = $handledStamps->getResult();

        $cacheItem->set($result);
        $cacheItem->expiresAfter($ttl);
        $this->cache->save($cacheItem);

        return $result;
    }
}
