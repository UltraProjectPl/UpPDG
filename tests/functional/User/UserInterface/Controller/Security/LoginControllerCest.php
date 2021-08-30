<?php
declare(strict_types=1);

namespace App\Tests\functional\User\UserInterface\Controller\Security;

use App\Tests\FunctionalTester;

class LoginControllerCest
{
    private const URL = '/auth/login';

    public function testResponseForInvalidRequest(FunctionalTester $I): void
    {
        $I->unsuccessfullySendApiPostRequest(self::URL);
        $I->seeResponseIsValidationErrorJson([
            'email' => [
                'This value should not be blank.'
            ],
            'password' => [
                'This value should not be blank.'
            ],
        ]);

        $I->unsuccessfullySendApiPostRequest(self::URL, [
            'email' => 'Invalid email',
            'password' => 'TestPassword123',
        ]);
        $I->seeResponseIsValidationErrorJson([
            'email' => [
                'This value is not a valid email address.'
            ],
        ]);
    }

    public function testSuccessfulResponse(FunctionalTester $I): void
    {
        $I->haveCreatedUser('valid@email.com', 'test123');

        $I->successfullySendApiPostRequest(self::URL, [
            'email' => 'valid@email.com',
            'password' => 'test123',
        ]);
    }
}