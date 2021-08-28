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
        Assertion::true($this->min->isSameCurrency($this->max));
        Assertion::greaterThan($this->min->getAmount(), 0);
        Assertion::greaterThan($this->max->getAmount(), 0);
        Assertion::greaterThan($this->max->getAmount(), $this->min->getAmount());
    }

    public function getMin(): Money
    {
        return $this->min;
    }

    public function getMax(): Money
    {
        return $this->max;
    }

    public function jsonSerialize(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}