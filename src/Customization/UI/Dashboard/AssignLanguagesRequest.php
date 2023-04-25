<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Type;

class AssignLanguagesRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Constraint
    {
        // todo allow only languages from AvailableLanguagesQuery
        return new All(new Type('string'));
    }
}