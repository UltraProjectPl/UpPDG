<?php
declare(strict_types=1);

namespace App\Employer\Application\Form\Dto;

use App\Employer\Domain\PaymentSpreads;
use Assert\Assertion;
use Money\Currency;
use Money\Money;

final class PaymentSpreadsDto
{
    public ?int $min = null;
    public ?int $max = null;
    /** @var non-empty-string  */
    public ?string $currency = null;

    public function toEntity(): PaymentSpreads
    {
        Assertion::notNull($this->currency);

        $min = new Money($this->min, new Currency($this->currency));
        $max = new Money($this->max, new Currency($this->currency));

        return new PaymentSpreads(min: $min, max: $max);
    }
}