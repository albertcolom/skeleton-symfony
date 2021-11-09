<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Listener;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Event\EventBus;
use Doctrine\ORM\Event\OnFlushEventArgs;

class DoctrinePublishDomainEventsOnFlushListener
{
    public function __construct(private EventBus $eventBus)
    {
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $unitOfWork = $eventArgs->getEntityManager()->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledEntityDeletions() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledCollectionDeletions() as $collection) {
            foreach ($collection as $entity) {
                $this->publishDomainEvent($entity);
            }
        }

        foreach ($unitOfWork->getScheduledCollectionUpdates() as $collection) {
            foreach ($collection as $entity) {
                $this->publishDomainEvent($entity);
            }
        }
    }

    private function publishDomainEvent(object $entity): void
    {
        if ($entity instanceof AggregateRoot && !$entity->domainEventsEmpty()) {
            $this->eventBus->publish(...$entity->pullDomainEvents());
        }
    }
}
