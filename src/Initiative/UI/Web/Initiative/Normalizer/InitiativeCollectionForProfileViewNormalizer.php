<?php

namespace App\Initiative\UI\Web\Initiative\Normalizer;

use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InitiativeCollectionForProfileViewNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private InitiativeBriefingNormalizer $initiativeNormalizer;

    public function __construct(InitiativeBriefingNormalizer $initiativeNormalizer)
    {
        $this->initiativeNormalizer = $initiativeNormalizer;
    }

    /**
     * @param InitiativeCollection $initiatives
     * @param null                 $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($initiatives, $format = null, array $context = []): array
    {
        // todo move pages property along with data prop
        $response = [];

        foreach ($initiatives->toArray() as $initiative) {
            $response[] = $this->initiativeNormalizer
                ->setWrapped(false)
                ->normalize($initiative, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof InitiativeCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
