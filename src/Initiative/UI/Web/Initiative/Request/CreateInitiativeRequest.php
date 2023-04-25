<?php

namespace App\Initiative\UI\Web\Initiative\Request;

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

final class CreateInitiativeRequest extends BaseRequest
{
    // todo use InitiativeContentModification fields
    public static function getConstraints(ContainerInterface $container): Collection
    {
        $mimeTypes = new MimeTypes();

        return new Collection([
            'type' => [
                new NotBlank(),
            ],
            'category' => [
                new NotBlank(),
                new Uuid(),
            ],
            'title' => [
                new NotBlank(),
            ],
            'description' => [
                new NotBlank(),
            ],
            'minimal_joined_people' => new Optional([
                new Positive(),
//                new NotBlank(),
            ]),
            'location' => new Optional([
//                new NotBlank(),
                new Callback(function (?string $location, ExecutionContext $context) {
                    if (!$location) {
                        return;
                    }

                    $match = preg_match(Location::REGEX, $location);

                    // TODO NotBlank not works for some reason, remove $location check there after solution found
                    if (!$match) {
                        $context->addViolation('Location must be lat[ANY_SEPARATOR_IGNORE_BRACKETS]lng');
                    }

//                    if (!$context->getRoot()['location_radius_value']) {
//                        $context->addViolation('Location must have location_radius_value');
//                    }

                    if (!$context->getRoot()['location_name']) {
                        $context->addViolation('Location must have location_name');
                    }
                }),
            ]),
            'location_name' => new Optional([
//                new NotBlank(),
            ]),
            'location_radius_value' => new Optional([
//                new NotBlank(),
            ]),
            'location_radius_unit' => new Optional([
//                new NotBlank(),
            ]),
            'date_start' => [
                new Callback(function ($date, ExecutionContext $context) {
                    if (!\DateTime::createFromFormat(\DateTime::ISO8601, $date)) {
                        $context->addViolation("$date is not at ISO8601 format.");
                    }
                }),
                new NotBlank(),
            ],
            'date_end' => [
                new Callback(function ($date, ExecutionContext $context) {
                    if (!\DateTime::createFromFormat(\DateTime::ISO8601, $date)) {
                        $context->addViolation("$date is not at ISO8601 format.");
                    }
                }),
                new NotBlank(),
            ],
            'images' => new Optional([
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
            'questionnaires' => new Optional([
                //todo
            ]),
        ]);
    }
}
