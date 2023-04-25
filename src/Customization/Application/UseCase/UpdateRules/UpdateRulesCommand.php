<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateRules;

final class UpdateRulesCommand
{
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function rules(): array
    {
        return $this->rules;
    }
}
