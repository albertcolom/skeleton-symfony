<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Doctrine\CustomType;

use App\Context\Foo\Domain\Write\Bar\ValueObject\BarId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class BarIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL([]);
    }

    public function getName(): string
    {
        return 'foo.bar_id';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): BarId
    {
        return BarId::fromString($value);
    }
}
