<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\TermsOfUse;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TermsOfUseCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private TermsOfUseNormalizer $normalizer;

    public function __construct(TermsOfUseNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param TermsOfUseCollectionPayload $termsCollection
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($termsCollection, $format = null, array $context = []): array
    {
        $response = [];

        /** @var TermsOfUse $terms */
        foreach ($termsCollection->get()->toArray() as $terms) {
            $response[(string) $terms->language()] = $this->normalizer
                ->setWrapped(false)
                ->normalize($terms, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof TermsOfUseCollectionPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
