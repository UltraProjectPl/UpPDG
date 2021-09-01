<?php
declare(strict_types=1);

namespace App\Employer\Domain;

use Assert\Assertion;
use JsonSerializable;
use Money\Money;

class PaymentSpreads implements JsonSerializable
{
    public function __construct(private Money $min, private Money $max)
    {
        Assertion::true($this->min->isSameCurrency($this->max), sprintf('Values must be of the same currency. Given "%s" and "%s"', $this->min->getCurrency(), $this->max->getCurrency()));
        Assertion::greaterThan($this->min->getAmount(), 0, 'The minimum value must be greater than 0');
        Assertion::greaterThan($this->max->getAmount(), $this->min->getAmount(), sprintf('The maximum value must be greater than the minimum value. Given; min: (%s) max: (%s)', $this->min->getAmount(), $this->max->getAmount()));
    }

    public function getMin(): Money
    {
        return $this->min;
    }

    public function getMax(): Money
    {
        return $this->max;
    }

    /** @return array<string, Money> */
    public function jsonSerialize(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}