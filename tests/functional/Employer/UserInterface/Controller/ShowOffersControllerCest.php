<?php

namespace App\Tests\functional\Employer\UserInterface\Controller;

use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class ShowOffersControllerCest
{
    private const URL = '/employer/offers';


    public function testSuccessfulResponse(FunctionalTester $I): void
    {
        $token = $I->amLoggedIn('valid@email.com', 'test123');

        $I->successfullySendApiGetRequest(self::URL, HttpCode::OK, $token);
        $I->canSeeResponseIsJson();
    }
}