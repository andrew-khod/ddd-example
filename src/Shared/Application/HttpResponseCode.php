<?php

declare(strict_types=1);

namespace App\Shared\Application;

use Symfony\Component\HttpFoundation\Response;

// We use 3rd party here, but it some kind of anti-corruption layer, because all response code calls are made with this unit
final class HttpResponseCode
{
    public static function conflict(): int
    {
        return Response::HTTP_CONFLICT;
    }

    public static function notFound(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    public static function unprocessableEntity(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    public static function forbidden(): int
    {
        return Response::HTTP_FORBIDDEN;
    }

    public static function unauthorized(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public static function badRequest(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
