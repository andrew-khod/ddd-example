<?php

namespace App\Identity\UI\Dashboard\User\Request;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;

final class CreateUserRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'email' => [
                new Email(),
                new NotBlank(),
            ],
            'username' => new Optional(),
            'permissions' => new NotBlank(),
        ]);
    }
}
