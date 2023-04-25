<?php

namespace App\Identity\Application\User\UseCase\LinkGoogleAuth;

interface GoogleAuth
{
    public function userByOneTimeCode(string $code): GoogleUser;
}
