<?php
declare(strict_types=1);

namespace App\Tests\Module\SharedKernel;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\Module\REST;
use Codeception\Util\HttpCode;

class ApiModule extends Module
{
    private REST $rest;

    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        $this->rest = $moduleContainer->getModule('REST');
    }

    public function seeResponseIsValidationErrorJson(array $errors): void
    {
        $expectedResponse = [
            'message' => 'Request does not meet validation requirements.',
            'errors' => [],
        ];

        foreach ($errors as $path => $pathErrors) {
            $expectedResponse['errors'][$path] = $pathErrors;
        }

        $this->rest->seeResponseContainsJson($expectedResponse);
    }

    public function successfullySendApiGetRequest(string $url): void
    {
        $this->rest->haveHttpHeader('Accept', 'application/json');
        $this->rest->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $this->rest->sendGET($url);
        $this->rest->seeResponseIsJson();
        $this->rest->seeResponseCodeIs(HttpCode::OK);
    }

    public function unsuccessfullySendApiGetRequest(string $url, int $code = 400): void
    {
        $this->rest->haveHttpHeader('Accept', 'application/json');
        $this->rest->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $this->rest->sendGET($url);
        $this->rest->seeResponseIsJson();
        $this->rest->seeResponseCodeIs($code);
    }

    public function successfullySendApiPostRequest(string $url, array $parameters = [], int $response = HttpCode::OK, ?string $token = null, array $files = []): void
    {
        $this->rest->haveHttpHeader('Accept', 'application/json');
        $this->rest->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        if (null !== $token) {
            $this->rest->haveHttpHeader('Authorization', $token);
        }
        $this->rest->sendPOST($url, $parameters, $files);
        $this->rest->seeResponseIsJson();
        $this->rest->seeResponseCodeIs($response);
    }

    public function unsuccessfullySendApiPostRequest(
        string $url,
        array $parameters = [],
        int $code = HttpCode::BAD_REQUEST,
        ?string $token = null,
        array $files = [],
    ): void {
        $this->rest->haveHttpHeader('Accept', 'application/json');
        $this->rest->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        if (null !== $token) {
            $this->rest->haveHttpHeader('Authorization', $token);
        }
        $this->rest->sendPOST($url, $parameters, $files);
        $this->rest->seeResponseIsJson();
        $this->rest->seeResponseCodeIs($code);
    }
}