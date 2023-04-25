<?php

namespace App\Identity\UI\Dashboard\User\Request\RecoverPassword;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

final class PasswordRecoveryConfirmationRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'token' => [
                new NotBlank(),
            ],
            'password' => [
                new NotCompromisedPassword(),
                new NotBlank(),
            ],
        ]);
    }
}
