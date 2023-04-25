<?php

namespace App\Identity\UI\Web\Customer\Request;

use App\Identity\Application\Customer\UseCase\UpdateCustomerProfile\CustomerProfileModification;
use App\Identity\Domain\Customer\Gender;
use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;

final class UpdateCustomerProfileRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        $mimeTypes = new MimeTypes();

        return new Collection([
            CustomerProfileModification::USERNAME => new Optional([
                new NotBlank(),
            ]),
            CustomerProfileModification::BIRTHDAY => new Optional([
                new Date(),
//                new NotBlank(),
            ]),
            CustomerProfileModification::GENDER => new Optional([
                new Choice(Gender::genders()),
//                new NotBlank(),
            ]),
            CustomerProfileModification::POSTAL => new Optional([
                new Length(['min' => 1])
                //                new NotBlank(),
            ]),
            CustomerProfileModification::PHOTO => new Optional([
                new File([
                    'mimeTypes' => [
                        ...$mimeTypes->getMimeTypes('jpg'),
                        ...$mimeTypes->getMimeTypes('png'),
                    ],
                ]),
                //                new NotBlank(),
            ]),
            CustomerProfileModification::NEW_PASSWORD => new Optional([
                new NotBlank(),
            ]),
        ]);
    }
}
