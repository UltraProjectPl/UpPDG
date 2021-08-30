<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Http;

use App\SharedKernel\UserInterface\Http\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ResponseFactory implements ResponseFactoryInterface
{

    public function data(mixed $data, int $status = Response::HTTP_OK): Response
    {
        return new JsonResponse([
            'data' => $data
        ], $status);
    }

    public function authorization(mixed $data, int $status = Response::HTTP_OK): Response
    {
        return new JsonResponse([
            'token' => $data
        ], $status);
    }

    public function create(mixed $data, int $status = Response::HTTP_CREATED): Response
    {
        return new JsonResponse([
            'message' => $data,
        ], $status);
    }

    public function error(mixed $errors, int $status = Response::HTTP_BAD_REQUEST): Response
    {
        return new JsonResponse([
            'message' => 'Request does not meet validation requirements.',
            'errors' => $errors,
        ], $status);
    }
}