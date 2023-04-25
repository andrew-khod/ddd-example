<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\Query;

use App\Identity\Domain\Customer\Customer;
use App\Initiative\Application\Initiative\Query\FilterNormalizer\FilterToCriteriaNormalizerFactory;
use Doctrine\Common\Collections\Expr\Comparison;

final class InitiativeByFilterCriteria implements InitiativeCriteria
{
    private array $fields = [];
    private array $operators = [];

    public const SORT = [
        'title',
        'description',
        'joined',
        'created',
    ];
    private ?string $sort = null;
    private int $page = 1;
    private array $originalFilter = [];
    private ?Customer $authenticatedCustomer = null;
    private bool $isLoadFromFirstPage = false;

    public function __construct(array $fields, string $sort = null, int $page = 1, ?Customer $authenticatedCustomer = null)
    {
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->originalFilter = $fields;
        $this->sort = $sort;
        $this->page = $page;

//        $this->fields['comments.archived_at'] = null;
//        $this->operators['comments.archived_at'] = 'IS';

        foreach ($fields as $field => $value) {
            if (!$value) {
                continue;
            }

            // todo improve that hack when have a time
            // todo or maybe that wouldn't be needed after using Doctrine Specification package
            if ($field !== 'text') {
                $normalizer = FilterToCriteriaNormalizerFactory::create($field, $value);
                $field = $normalizer->field();

                $this->fields[$field] = $normalizer->value();
                $this->operators[$field] = $normalizer->operator();
                continue;
            }

            $value = sprintf('%%%s%%', $value);
            $this->fields['title'] = $value;
            $this->fields['description'] = $value;
            $this->operators['title'] = Comparison::CONTAINS;
            $this->operators['description'] = Comparison::CONTAINS;
        }
    }

    public function filter(): array
    {
        return $this->originalFilter;
    }

    // TODO think about better way to exclude archived, expired initiatives
    public function excludeArchived(): self
    {
        $this->fields['is_archived'] = false;
        $this->operators['is_archived'] = Comparison::EQ;
        return $this;
    }

    public function isExcludeArchived(): bool
    {
        return key_exists('is_archived', $this->fields) ? !$this->fields['is_archived'] : false;
    }

    public function toArray(): array
    {
        return $this->fields;
    }

    public function operatorFor(string $field): ?string
    {
        return key_exists($field, $this->operators)
            ? $this->operators[$field]
            : null;
    }

    public function relations(): array
    {
        // TODO INTERFACE METHOD
        return [
            'p' => 'participants',
            'c2' => 'customer',
            'c' => 'comments',
            'i' => 'images',
            'c1' => 'categories',
        ];
//        return [];
//        return [Customer::class, Comment::class, Image::class];
    }

    public function relationsConditions(): array
    {
        return [
            'c' => 'c.archived_at IS NULL',
        ];
    }

    // TODO think about to move to InitiativeByCriteriaQuery
    public function sort(): ?string
//    public function sort(): array|string
    {
        return $this->sort;
//        $rules = ['c.created'];
//
//        if ($this->sort === 'joined') {
////            return 'count(participants.id)';
//            $rules['joinedCount'] = 'p.id';
////            $rules[] = ['joinedCount' => 'p.id'];
//            return $rules;
////            return ['joinedCount' => 'count(p.id)'];
//        }
//
//        if ($this->sort) {
//            $rules[] = sprintf('%s.%s', BaseQuery::ALIAS, $this->sort);
//            return $rules;
//        }
//
//        return $rules;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function isLoadFromFirstPage(): bool
    {
        return $this->isLoadFromFirstPage;
    }

    public function setLoadFromFirstPage(bool $option): void
    {
        $this->isLoadFromFirstPage = $option;
    }

    public function authenticatedCustomer(): ?Customer
    {
        return $this->authenticatedCustomer;
    }
}
