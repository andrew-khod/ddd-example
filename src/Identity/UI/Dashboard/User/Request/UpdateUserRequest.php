<?php

namespace App\Identity\UI\Dashboard\User\Request;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

final class UpdateUserRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'username' => new Optional(),
            'is_superadmin' => new Optional(new Type('bool')),
            'permissions' => new Optional(),
//            'permissions' => new NotBlank(),
        ]);
    }
}
