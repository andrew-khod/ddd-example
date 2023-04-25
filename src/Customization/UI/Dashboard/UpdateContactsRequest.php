<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\Infrastructure\Doctrine\Query\AssignedToCompanyLanguagesQuery;
use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;

class UpdateContactsRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): All
    {
        $query = $container->get(AssignedToCompanyLanguagesQuery::class);
        $query->includeDeleted();
        $languages = $query->query();
        $fields = [];

        foreach ($languages as $language) {
            $fields[(string) $language] = new Optional(new All(new Collection([
                'fields' => [
                    'type' => new NotBlank(),
                    'value' => new NotBlank(),
                ]
            ])));
        }

        return new All([
            'constraints' => [
                new Collection([
                    'fields' => $fields,
                ])
            ]
        ]);
    }
}