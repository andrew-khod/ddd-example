<?php

namespace App\Initiative\Application\Category;

use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Category\CategoryId;

interface CategoryEntityManager
{
    public function persist(): void;
    public function create(Category ...$categories): void;
    public function nextId(): CategoryId;
    public function remove(Category $category): void;
}