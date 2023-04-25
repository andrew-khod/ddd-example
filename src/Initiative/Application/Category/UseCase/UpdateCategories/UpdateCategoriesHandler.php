<?php

declare(strict_types=1);

namespace App\Initiative\Application\Category\UseCase\UpdateCategories;

use App\Initiative\Application\Category\CategoryEntityManager;
use App\Initiative\Application\Category\Query\CategoryByCriteriaQuery;
use App\Initiative\Application\Category\Query\CategoryByIdCriteria;
use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Category\CategoryCollection;
use App\Initiative\Domain\Category\CategoryId;
use App\Initiative\Domain\Category\NewCategoryTranslation;
use App\Initiative\Domain\Initiative\Initiative;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Domain\Language;

final class UpdateCategoriesHandler
{
    private CategoryByCriteriaQuery $categoryByCriteriaQuery;
    private AssignedToCompanyLanguagesQuery $allLanguageQuery;
    private CategoryEntityManager $categoryEntityManager;

    public function __construct(CategoryByCriteriaQuery         $categoryByCriteriaQuery,
                                AssignedToCompanyLanguagesQuery $allLanguageQuery,
                                CategoryEntityManager           $categoryEntityManager)
    {
        $this->categoryByCriteriaQuery = $categoryByCriteriaQuery;
        $this->allLanguageQuery = $allLanguageQuery;
        $this->categoryEntityManager = $categoryEntityManager;
    }

    // TODO a lot of queries there. optimize it to be batch!
    public function handle(UpdateCategoriesCommand $command): void
    {
        $categoriesToUpdate = $command->toUpdate();
        $categoriesToAdd = $command->toAdd();
        $ids = array_map(fn(string $id) => new CategoryId($id), array_keys($categoriesToUpdate));
        $languages = $this->allLanguageQuery->query();
        $backupToCategory = $command->toBackup();
        $newCategoriesIds = [];

        $existingCategories = $this->categoryByCriteriaQuery->queryMultiple(new CategoryByIdCriteria(...$ids));
        $removableCategories = $this->categoryByCriteriaQuery->queryMultiple(
            new CategoryByIdCriteria(...array_map(fn(string $id) => new CategoryId($id), $command->toRemove()))
        );

        array_map(function (Category $category) use ($categoriesToUpdate, $languages) {
            $id = (string) $category->id();

            if (key_exists($id, $categoriesToUpdate)) {
                // todo think about encapsulation in Category object, passing Domain Dto to it
                foreach ($categoriesToUpdate[$id] as $language => $name) {
                    $language = array_values(array_filter($languages, fn(Language $l) => $l->name() === $language));

                    if (count($language)) {
                        $category->renameOrCreateTranslation($language[0], $name);
                    }
                }
            }
        }, $existingCategories->toArray());

        foreach ($categoriesToAdd as $id => $translations) {
//            $translations = array_map(fn(string $language, string $name) => new NewCategoryTranslation(), $translations);
            $newTranslations = [];

            foreach ($translations as $language => $name) {
                $language = array_values(array_filter($languages, fn(Language $l) => $l->name() === $language));

                if (count($language)) {
                    $newTranslations[] = new NewCategoryTranslation($name, $language[0]);
                }
            }

            $category = new Category($this->categoryEntityManager->nextId(), ...$newTranslations);

            $this->categoryEntityManager->create($category);

            $newCategoriesIds[$id] = $category;
        }

        if ($backupToCategory) {
            if (key_exists($backupToCategory, $newCategoriesIds)) {
                $backupToCategory = $newCategoriesIds[$backupToCategory];
            } else {
                $backupToCategory = $this->categoryByCriteriaQuery->queryOne(new CategoryByIdCriteria(new CategoryId($backupToCategory)));
            }

            array_map(function (Category $category) use ($backupToCategory) {
                array_map(function (Initiative $initiative) use ($backupToCategory) {
                    $initiative->moveToCategories(new CategoryCollection($backupToCategory));
                }, $category->initiatives()->toArray());
                $this->categoryEntityManager->remove($category);
            }, $removableCategories->toArray());
        }

        $this->categoryEntityManager->persist();
    }
}
