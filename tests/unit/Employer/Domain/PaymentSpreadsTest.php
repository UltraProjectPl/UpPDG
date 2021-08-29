<?php
declare(strict_types=1);

namespace App\Tests\unit\Employer\Domain;

use App\Employer\Domain\PaymentSpreads;
use Codeception\Test\Unit;
use InvalidArgumentException;
use Money\Currency;
use Money\Money;

class PaymentSpreadsTest extends Unit
{
    public function testNotEqualCurrency(): void
    {
        $min = new Money(100, new Currency('PLN'));
        $max = new Money(1000, new Currency('EUR'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Values must be of the same currency. Given "PLN" and "EUR"');

        new PaymentSpreads($min, $max);
    }

    public function testMinZero(): void
    {
        $min = new Money(0, new Currency('PLN'));
        $max = new Money(1000, new Currency('PLN'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minimum value must be greater than 0');

        new PaymentSpreads($min, $max);
    }

    public function testMinIsGreaterThanMax(): void
    {
        $min = new Money(100, new Currency('PLN'));
        $max = new Money(10, new Currency('PLN'));
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The maximum value must be greater than the minimum value. Given; min: (100) max: (10)');

        new PaymentSpreads($min, $max);
    }

    public function testProperValue(): void
    {
        $min = new Money(100, new Currency('PLN'));
        $max = new Money(1000, new Currency('PLN'));

        $paymentSpreads = new PaymentSpreads($min, $max);

        $this->assertEquals($min, $paymentSpreads->getMin());
        $this->assertEquals($max, $paymentSpreads->getMax());
    }

    public function testSerialize(): void
    {
        $min = new Money(100, new Currency('PLN'));
        $max = new Money(1000, new Currency('PLN'));

        $paymentSpreads = new PaymentSpreads($min, $max);

        $this->assertEquals(json_decode(json_encode($paymentSpreads), true), [
            'min' => [
                'amount' => '100',
                'currency' => 'PLN'
            ],
            'max' => [
                'amount' => '1000',
                'currency' => 'PLN'
            ],
        ]);
    }
}