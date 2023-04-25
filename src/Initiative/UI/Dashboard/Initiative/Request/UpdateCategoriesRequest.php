<?php

namespace App\Initiative\UI\Dashboard\Initiative\Request;

use App\Shared\Infrastructure\Doctrine\Query\AssignedToCompanyLanguagesQuery;
use App\Shared\UI\Http\Request\BaseRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContext;

final class UpdateCategoriesRequest extends BaseRequest
{
    public static function getConstraints(ContainerInterface $container)
    {
        $query = $container->get(AssignedToCompanyLanguagesQuery::class);
        $query->includeDeleted();
        $languages = $query->query();
        $fields = [];

        foreach ($languages as $language) {
            $fields[(string) $language] = new Optional(new NotBlank());
        }

        return new Collection([
            'categories' => new All([
                'constraints' => [
                    new Callback(function ($translations, ExecutionContext $context) {
//                    $categories = $context->getRoot();
//                        $id = trim($context->getPropertyPath(), '[]');
                        $id = str_ireplace(['[', ']', 'categories'], '', $context->getPropertyPath());
                        $violations = $context->getValidator()->validate($id, new Uuid());

                        if ($violations->count() > 0 && !preg_match('/new_\d+/i', $id)) {
                            $context->addViolation($violations);
                        }
                    }),
                    new Collection([
                        'fields' => $fields,
                    ])
                ]
            ]),
            'to_remove' => new All(new Uuid()),
            'to_backup' => [],
//            'to_backup' => new Uuid(),
        ]);
    }
}
