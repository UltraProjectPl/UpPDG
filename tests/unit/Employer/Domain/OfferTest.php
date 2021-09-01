<?php

declare(strict_types=1);

namespace App\Tests\unit\Employer\Domain;

use App\Employer\Domain\Offer;
use App\Employer\Domain\PaymentSpreads;
use App\User\Domain\User;
use Codeception\Test\Unit;
use Money\Currency;
use Money\Money;

class OfferTest extends Unit
{
    public function testSerialize(): void
    {
        $user = $this->createMock(User::class);
        $min = new Money(100, new Currency('PLN'));
        $max = new Money(1000, new Currency('PLN'));

        $paymentSpreads = new PaymentSpreads($min, $max);

        $offer = new Offer(
            creator: $user,
            title: 'Offer Name',
            companyName: 'Company name',
            paymentSpreads: $paymentSpreads,
            city: 'City',
        );

        $this->assertEquals(json_decode(json_encode($offer), true), [
            'id' => $offer->getId()->toString(),
            'creator' => [],
            'title' => $offer->getTitle(),
            'companyName' => $offer->getCompanyName(),
            'city' => $offer->getCity(),
            'paymentSpreads' => [
                'min' => [
                    'amount' => 100,
                    'currency' => 'PLN',
                ],
                'max' => [
                    'amount' => 1000,
                    'currency' => 'PLN',
                ],
            ],
            'remoteWorkPossible' => $offer->isRemoteWorkPossible(),
            'remoteWorkOnly' => $offer->isRemoteWorkOnly(),
            'active' => $offer->isActive(),
            'nip' => $offer->getNip(),
            'tin' => $offer->getTin(),
            'createdAt' => (array) $offer->getCreatedAt(),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);


    }
}
