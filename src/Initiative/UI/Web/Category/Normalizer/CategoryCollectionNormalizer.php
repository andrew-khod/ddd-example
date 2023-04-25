<?php

namespace App\Initiative\UI\Web\Category\Normalizer;

use App\Initiative\Domain\Category\CategoryCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CategoryCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private CategoryNormalizer $categoryNormalizer;

    public function __construct(CategoryNormalizer $categoryNormalizer)
    {
        $this->categoryNormalizer = $categoryNormalizer;
    }

    /**
     * @param CategoryCollection $categories
     * @param null               $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($categories, $format = null, array $context = []): array
    {
        $response = [];

        foreach ($categories->toArray() as $category) {
            $response[] = $this->categoryNormalizer
                ->setWrapped(false)
                ->normalize($category, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CategoryCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
