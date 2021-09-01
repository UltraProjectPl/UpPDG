<?php

namespace App\Tests\functional\Employer\UserInterface\Controller;

use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class ActiveOfferControllerCest
{
    private const URL = '/employer/offer/active/';

    public function testResponseForInvalidRequest(FunctionalTester $I): void
    {
        $token = $I->amLoggedIn('test@email.com', 'qwe123');

        $offer = $I->haveCreatedOffer($token);

        $I->unsuccessfullySendApiPostRequest(self::URL . $offer->getId()->toString(), [], HttpCode::UNAUTHORIZED);
        $I->seeResponseIsValidationErrorJson([
            'Full authentication is required to access this resource.',
        ]);

        $I->unsuccessfullySendApiPostRequest(self::URL . $offer->getId()->toString(), [], HttpCode::BAD_REQUEST, $token);
        $I->seeResponseIsValidationErrorJson([
            "Offer '{$offer->getId()->toString()}' is already active."
        ]);

        $offer = $I->haveCreatedOffer($token, null, null, null, null, null, null, false);

        $token = $I->amLoggedIn('test2@email.com', 'qwe123');
        $I->unsuccessfullySendApiPostRequest(self::URL . $offer->getId()->toString(), [], HttpCode::BAD_REQUEST, $token);
        $I->seeResponseIsValidationErrorJson([
            "You are not the creator of the offer '{$offer->getId()->toString()}'."
        ]);
    }

    public function testSuccessfulResponse(FunctionalTester $I): void
    {
        $token = $I->amLoggedIn('test@email.com', 'qwe123');

        $offer = $I->haveCreatedOffer($token, null, null, null, null, null, null, false);

        $I->successfullySendApiPostRequest(self::URL . $offer->getId()->toString(), [], HttpCode::OK, $token);
        $I->seeResponseContainsJson([
            'data' => ['active']
        ]);

        $I->seeOfferHasActive($offer->getId());
    }
}