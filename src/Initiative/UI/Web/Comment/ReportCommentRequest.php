<?php

namespace App\Initiative\UI\Web\Comment;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;

class ReportCommentRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'reason' => new NotBlank(),
            'message' => new Optional([
//                new NotBlank(),
            ]),
            'url' => new NotBlank(),
        ]);
    }
}