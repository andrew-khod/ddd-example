<?php

namespace App\Identity\UI\Web\Customer\Request;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

final class SwitchLanguageRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'language' => [
                new NotBlank(),
            ],
        ]);
    }
}
