<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\Infrastructure\Doctrine\Query\AssignedToCompanyLanguagesQuery;
use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Url;

class UpdatePrivacyPolicyRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container): Collection
    {
        $query = $container->get(AssignedToCompanyLanguagesQuery::class);
        $query->includeDeleted();
        $languages = $query->query();
        $fields = [];

        foreach ($languages as $language) {
            $fields[(string) $language] = new Optional([new NotBlank(), new Url()]);
        }

        return new Collection([
            'fields' => $fields,
        ]);
    }
}