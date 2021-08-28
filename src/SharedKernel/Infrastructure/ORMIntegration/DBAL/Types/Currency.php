<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\ORMIntegration\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Money\Currency as MoneyCurrency;
use InvalidArgumentException;

class Currency extends Type
{
    public const NAME = 'currency';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'length' => 3,
            'fixed' => true,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?MoneyCurrency
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof MoneyCurrency) {
            return $value;
        }

        try {
            $currency = new MoneyCurrency($value);
        } catch (InvalidArgumentException) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $currency;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof MoneyCurrency) {
            return $value->getCode();
        }

        try {
            $currency = new MoneyCurrency($value);
        } catch (InvalidArgumentException) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $currency->getCode();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}