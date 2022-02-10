<?php

declare(strict_types=1);

namespace App\Shared\Domain\QueryParams;

class QueryParams
{
    private Offset $offset;
    private Limit $limit;

    public function __construct()
    {
        $this->offset = Offset::create();
        $this->limit = Limit::create();
    }

    public static function create(): self
    {
        return new self();
    }

    public static function fromArray(array $params): self
    {
        return self::create()
            ->setOffset(isset($params['offset']) ? (int)$params['offset'] : 0)
            ->setLimit(isset($params['limit']) ? (int)$params['limit'] : -1);
    }

    public function offset(): Offset
    {
        return $this->offset;
    }

    public function limit(): Limit
    {
        return $this->limit;
    }

    public function hasOffset(): bool
    {
        return 0 !== $this->offset()->value();
    }

    public function hasLimit(): bool
    {
        return -1 !== $this->limit()->value();
    }

    public function setOffset(int $limit): self
    {
        $this->offset = Offset::create($limit);
        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = Limit::create($limit);
        return $this;
    }

    public function toArray(): array
    {
        return [
            'offset' => $this->offset->value(),
            'limit' => $this->limit->value(),
        ];
    }
}
