<?php

namespace App\Initiative\UI\Dashboard\Initiative\Normalizer;

use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Category\CategoryTranslation;
use App\Initiative\UI\Dashboard\Initiative\Controller\CategoryCollectionPayload;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use stdClass;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CategoryCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param CategoryCollectionPayload $categories
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($categories, $format = null, array $context = []): array
    {
        $response = [];

        /** @var Category $category */
        foreach ($categories->get()->toArray() as $category) {
            /** @var CategoryTranslation $translation */
            $translations = $category->translations()->toArray();

            if (!count($translations)) {
                $response[(string) $category->id()] = new StdClass();
//                $response[(string) $category->id()] = [];
//                continue;
            }

            foreach ($translations as $translation) {
                $response[(string) $category->id()][$translation->language()] = $translation->name();
            }
        }

        return parent::normalize($response, $format, [
            ObjectNormalizer::PRESERVE_EMPTY_OBJECTS => true,
        ]);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CategoryCollectionPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
