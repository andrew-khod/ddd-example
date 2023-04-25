<?php

namespace App\Customization\UI\Web;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;

class CreateAccessibilityFeedbackRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'email' => new Optional(new Email()),
            'message' => new NotBlank(),
            'section' => new Optional(new NotBlank()),
//            'section' => new NotBlank(),
            'name' => new Optional(new NotBlank()),
//            'name' => new NotBlank(),
            'captcha' => new NotBlank(),
        ]);
    }
}