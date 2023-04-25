<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateTermsOfUseRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'fields' => [
                'en' => new Collection([
                    'fields' => [
                        'title' => new NotBlank(),
                        'description' => new NotBlank(),
                    ]
                ]),
                'fi' => new Collection([
                    'fields' => [
                        'title' => new NotBlank(),
                        'description' => new NotBlank(),
                    ]
                ]),
            ]
        ]);
    }
}