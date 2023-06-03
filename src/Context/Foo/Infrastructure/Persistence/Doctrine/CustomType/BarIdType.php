<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Doctrine\CustomType;

use App\Context\Foo\Domain\Bar\ValueObject\BarId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class BarIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL(['length' => 16, 'fixed' => true]);
    }

    public function getName(): string
    {
        return 'foo.bar_id';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof BarId) {
            return $value->optimizedId();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): BarId
    {
        return BarId::fromBinary($value);
    }
}
