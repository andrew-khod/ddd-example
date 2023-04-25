<?php

namespace App\Initiative\UI\Web\Category\Normalizer;

use App\Initiative\Domain\Category\Category;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CategoryNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ActiveLanguage $activeLanguage;

    public function __construct(ActiveLanguage $activeLanguage)
    {
        $this->activeLanguage = $activeLanguage;
    }

    /**
     * @param Category $category
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($category, $format = null, array $context = []): array
    {
        return parent::normalize([
            'id' => (string) $category->id(),
            'name' => $category->name($this->activeLanguage),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Category;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
