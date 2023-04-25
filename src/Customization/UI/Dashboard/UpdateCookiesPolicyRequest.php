<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\Infrastructure\Doctrine\Query\AssignedToCompanyLanguagesQuery;
use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;

class UpdateCookiesPolicyRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        $query = $container->get(AssignedToCompanyLanguagesQuery::class);
        $query->includeDeleted();
        $languages = $query->query();
        $fields = [];

        foreach ($languages as $language) {
            $fields[(string) $language] = new Optional(new Collection([
                'fields' => [
                    'title' => new NotBlank(),
                    'description' => new NotBlank(),
                ]
            ]));
        }

        return new Collection([
            'fields' => $fields,
        ]);
    }
}