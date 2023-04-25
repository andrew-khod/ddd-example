<?php

namespace App\Initiative\UI\Web\Initiative\Request;

use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\InitiativeContentModification;
use App\Initiative\Domain\Initiative\Location;
use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContext;

final class UpdateInitiativeRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        $mimeTypes = new MimeTypes();

        return new Collection([
//            InitiativeContentModification::TYPE => new Optional([
//                new NotBlank(),
//            ]),
            InitiativeContentModification::CATEGORY => new Optional([
                new NotBlank(),
                new Uuid(),
            ]),
            InitiativeContentModification::TITLE => new Optional([
                new NotBlank(),
            ]),
            InitiativeContentModification::DESCRIPTION => new Optional([
                new NotBlank(),
            ]),
            InitiativeContentModification::MINIMAL_JOINED_PEOPLE => new Optional([
//                new NotBlank(),
                new Positive(),
            ]),
            InitiativeContentModification::LOCATION => new Optional([
                new Callback(function (?string $location, ExecutionContext $context) {
                    if (!$location) {
                        return;
                    }

                    $match = preg_match(Location::REGEX, $location);

                    if ($location && !$match) {
                        $context->addViolation('Location must be lat[ANY_SEPARATOR_IGNORE_BRACKETS]lng');
                    }

//                    if (!$context->getRoot()[InitiativeContentModification::LOCATION_RADIUS_VALUE]) {
//                        $context->addViolation('Specify radius value');
//                    }

                    if (!$context->getRoot()[InitiativeContentModification::LOCATION_RADIUS_UNIT]) {
                        $context->addViolation('Specify radius unit');
                    }
                }),
//                new NotBlank(),
            ]),
            InitiativeContentModification::LOCATION_NAME => new Optional([

            ]),
            InitiativeContentModification::LOCATION_RADIUS_VALUE => new Optional([
                new Callback(function (?string $value, ExecutionContext $context) {
                    if (!$value) {
                        return;
                    }

                    // TODO find a better way to handle conditional validation rules
                    if (!$context->getRoot()[InitiativeContentModification::LOCATION]) {
                        $context->addViolation('Specify location');
                    }

                    if (!$context->getRoot()[InitiativeContentModification::LOCATION_RADIUS_UNIT]) {
                        $context->addViolation('Specify raduis unit');
                    }
                }),
//                new NotBlank(),
            ]),
            InitiativeContentModification::LOCATION_RADIUS_UNIT => new Optional([
                new Callback(function (?string $unit, ExecutionContext $context) {
                    // todo implement when needed
                    return;

//                    if (!$unit) {
//                        return;
//                    }
//
//                    if (!$context->getRoot()[InitiativeContentModification::LOCATION]) {
//                        $context->addViolation('Specify location');
//                    }
//
//                    if (!$context->getRoot()[InitiativeContentModification::LOCATION_RADIUS_VALUE]) {
//                        $context->addViolation('Specify raduis value');
//                    }
                }),
                //                new NotBlank(),
            ]),
            InitiativeContentModification::DATE_START => new Optional([
                new Callback(function ($date, ExecutionContext $context) {
                    if (!\DateTime::createFromFormat(\DateTime::ISO8601, $date)) {
                        $context->addViolation("$date is not at ISO8601 format.");
                    }
                }),
                new NotBlank(),
            ]),
            InitiativeContentModification::DATE_END => new Optional([
                new Callback(function ($date, ExecutionContext $context) {
                    if (!\DateTime::createFromFormat(\DateTime::ISO8601, $date)) {
                        $context->addViolation("$date is not at ISO8601 format.");
                    }
                }),
                new NotBlank(),
            ]),
            InitiativeContentModification::IMAGES => new Optional([
                new All([
                    new File([
                        'mimeTypes' => [
                            ...$mimeTypes->getMimeTypes('jpg'),
                            ...$mimeTypes->getMimeTypes('png'),
                        ],
                        'maxSize' => '10M',
                    ]),
                ]),
//                new NotBlank(),
            ]),
            InitiativeContentModification::IMAGES_TO_REMOVE => new Optional([
                new All([
                    new Uuid(),
                ]),
//                new NotBlank(),
            ]),
            'questionnaires' => new Optional([
                //todo
            ]),
        ]);
    }
}
