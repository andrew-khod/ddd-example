<?php

namespace App\Initiative\UI\Dashboard\Initiative\Request;

use App\Initiative\Application\Initiative\Query\InitiativeByFilterCriteria;
use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContext;

final class FilterInitiativeListRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        return new Collection([
            'filter' => new Optional(new Collection([
                'text' => new Optional([
//                    new Length(['allowEmptyString' => true]),
                    new NotBlank(),
                ]),
                'categories' => new Optional([
                    new All(new Uuid()),
                    new NotBlank(),
                ]),
                'date_start' => new Optional([
                    new Callback(function ($date, ExecutionContext $context) {
                        if (!\DateTime::createFromFormat(\DateTime::ISO8601, $date)) {
                            $context->addViolation("$date is not at ISO8601 format.");
                        }
                    }),
                    new NotBlank(),
                    new LessThan(['propertyPath' => 'date_end']),
                ]),
                'date_end' => new Optional([
                    new Callback(function ($date, ExecutionContext $context) {
                        if (!\DateTime::createFromFormat(\DateTime::ISO8601, $date)) {
                            $context->addViolation("$date is not at ISO8601 format.");
                        }
                    }),
                    new NotBlank(),
                ]),
// todo think between two date validation approaches
//                'date_start' => new Optional([
//                    new DateTime(),
//                    new LessThan(['propertyPath' => 'date_end']),
//                    new NotBlank(),
//                ]),
//                'date_end' => new Optional([
//                    new DateTime(),
//                    new NotBlank(),
//                ]),
            ])),
            'sort' => new Optional([
                new Choice(InitiativeByFilterCriteria::SORT),
            ]),
        ]);
    }
}
