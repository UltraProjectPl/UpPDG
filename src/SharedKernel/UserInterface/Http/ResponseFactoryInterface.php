<?php
declare(strict_types=1);

namespace App\SharedKernel\UserInterface\Http;

use Symfony\Component\HttpFoundation\Response;

interface ResponseFactoryInterface
{
    public function data(mixed $data, int $status = Response::HTTP_OK): Response;
    public function authorization(mixed $data, int $status = Response::HTTP_OK): Response;
    public function create(mixed $data, int $status = Response::HTTP_CREATED): Response;
    public function error(mixed $errors, int $status = Response::HTTP_BAD_REQUEST): Response;
}