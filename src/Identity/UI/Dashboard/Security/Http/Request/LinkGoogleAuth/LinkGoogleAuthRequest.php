<?php

namespace App\Identity\UI\Dashboard\Security\Http\Request\LinkGoogleAuth;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

final class LinkGoogleAuthRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'code' => [
                new NotBlank(),
            ],
        ]);
    }
}
