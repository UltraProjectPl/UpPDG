<?php

declare(strict_types=1);

namespace App\Tests\functional\Employer\UserInterface\Controller;

use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class AddOfferControllerCest
{
    private const URL = '/employer/offer/add';

    public function testResponseForInvalidRequest(FunctionalTester $I): void
    {
        $I->unsuccessfullySendApiPostRequest(self::URL, [], HttpCode::UNAUTHORIZED);
        $I->seeResponseIsValidationErrorJson([
            'Full authentication is required to access this resource.',
        ]);

        $token = $I->amLoggedIn('valid@email.com', 'test123');
        $I->unsuccessfullySendApiPostRequest(self::URL, [], HttpCode::BAD_REQUEST, $token);
        $I->seeResponseIsValidationErrorJson([
            'title' => [
                'This value should not be blank.',
            ],
            'companyName' => [
                'This value should not be blank.',
            ],
            'paymentSpreads' => [
                'This value should not be blank.',
            ],
            'city' => [
                'This value should not be blank.',
            ],
        ]);
    }

    public function testSuccessfulResponse(FunctionalTester $I): void
    {
        $token = $I->amLoggedIn('valid@email.com', 'test123');

        $I->successfullySendApiPostRequest(self::URL, [
            'title' => 'Test2',
            'companyName' => 'companytest',
            'paymentSpreads' => [
                'min' => 2000,
                'max' => 3000,
                'currency' => 'PLN',
            ],
            'city' => 'Warszawa',
        ], HttpCode::CREATED, $token);
        $I->seeResponseContainsJson([
            'message' => [
                'title' => 'Test2',
                'companyName' => 'companytest',
                'paymentSpreads' => [],
                'city' => 'Warszawa',
            ]
        ]);
    }
}
