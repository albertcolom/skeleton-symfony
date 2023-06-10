<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Doctrine\CustomType;

use App\Context\Foo\Domain\Write\ValueObject\FooId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class FooIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL([]);
    }

    public function getName(): string
    {
        return 'foo.foo_id';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): FooId
    {
        return FooId::fromString($value);
    }
}
