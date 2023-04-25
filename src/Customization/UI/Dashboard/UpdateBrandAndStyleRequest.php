<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Url;

class UpdateBrandAndStyleRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
//            'name' => new Optional()
            'name' => new Optional(new NotBlank()),
            'alias' => new Optional(new NotBlank()),
            'url' => new Optional([
                new NotBlank(),
                new Url(),
            ]),
            'color' => new Optional([
                new NotBlank(),
                new Length(6),
            ]),
            'footer' => new Optional(new NotBlank()),
//            'footer' => new NotBlank(),
//            'fields' => [
//                'en' => new Url(),
//                'fi' => new Url(),
//            ]
        ]);
    }
}