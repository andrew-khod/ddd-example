<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Optional;

class UpdateBrandAndStyleHeaderRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        $mimeTypes = new MimeTypes();

        return new Collection([
            'header' => new Optional(new File([
                'mimeTypes' => [
                    ...$mimeTypes->getMimeTypes('jpg'),
                    ...$mimeTypes->getMimeTypes('png'),
                ],
            ])),
            'logo' => new Optional(new File([
                'mimeTypes' => [
                    ...$mimeTypes->getMimeTypes('jpg'),
                    ...$mimeTypes->getMimeTypes('png'),
//                    ...$mimeTypes->getMimeTypes('svg'),
                ],
            ])),
            'logo_second' => new Optional(new File([
                'mimeTypes' => [
                    ...$mimeTypes->getMimeTypes('jpg'),
                    ...$mimeTypes->getMimeTypes('png'),
//                    ...$mimeTypes->getMimeTypes('svg'),
                ],
            ]))
        ]);
    }
}