<?php

declare(strict_types=1);

namespace App\Tests\functional\User\UserInterface\Controller\Security;


use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class RegisterControllerCest
{
    private const URL = '/auth/register';

    public function testResponseForInvalidRequest(FunctionalTester $I): void
    {
        $I->unsuccessfullySendApiPostRequest(self::URL);
        $I->seeResponseIsValidationErrorJson([
            'email' => [
                'This value should not be blank.'
            ],
            'firstName' => [
                'This value should not be blank.'
            ],
            'lastName' => [
                'This value should not be blank.'
            ],
            'password' => [
                'This value should not be blank.'
            ],
        ]);

        $I->unsuccessfullySendApiPostRequest(self::URL, [
            'email' => 'Invalid email',
            'firstName' => 'Name',
            'lastName' => 'Surname',
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
        $I->successfullySendApiPostRequest(self::URL, [
            'email' => 'valid@email.com',
            'firstName' => 'Name',
            'lastName' => 'Surname',
            'password' => 'test123',
        ], HttpCode::CREATED);

        $I->seeResponseContainsJson([
            'message' => [
                'email' => 'valid@email.com',
            ]
        ]);
    }
}
