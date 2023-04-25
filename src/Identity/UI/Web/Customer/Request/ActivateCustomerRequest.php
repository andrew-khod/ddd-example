<?php

namespace App\Identity\UI\Web\Customer\Request;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ActivateCustomerRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'token' => [
                new NotBlank(),
            ],
            'password' => [
                new NotBlank(),
            ],
            'username' => [
                new NotBlank(),
            ],
        ]);
    }
}
