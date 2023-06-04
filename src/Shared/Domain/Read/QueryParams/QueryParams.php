<?php

declare(strict_types=1);

namespace App\Shared\Domain\Read\QueryParams;

class QueryParams
{
    private const DEFAULT_LIMIT = -1;
    private const DEFAULT_OFFSET = 0;
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
            ->setOffset(isset($params['offset']) ? (int)$params['offset'] : self::DEFAULT_OFFSET)
            ->setLimit(isset($params['limit']) ? (int)$params['limit'] : self::DEFAULT_LIMIT);
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
        return self::DEFAULT_OFFSET !== $this->offset()->value;
    }

    public function hasLimit(): bool
    {
        return self::DEFAULT_LIMIT !== $this->limit()->value;
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
            'offset' => $this->offset->value,
            'limit' => $this->limit->value,
        ];
    }
}
