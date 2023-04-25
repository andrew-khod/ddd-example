<?php

namespace App\Initiative\UI\Web\Initiative\Normalizer;

use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InitiativeCollectionForMapViewNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private InitiativeBriefingNormalizer $initiativeNormalizer;

    public function __construct(InitiativeBriefingNormalizer $initiativeNormalizer)
    {
        $this->initiativeNormalizer = $initiativeNormalizer;
    }

    /**
     * @param array $initiatives
     * @param null                 $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($initiatives, $format = null, array $context = []): array
    {
        // todo move pages property along with data prop
        $response = [
            'items' => [],
            'no_location' => $initiatives['no_location'],
        ];

        foreach ($initiatives['items']->toArray() as $initiative) {
            $response['items'][] = $this->initiativeNormalizer
                ->setWrapped(false)
                ->normalize($initiative, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return is_array($data) && key_exists('items', $data) && $data['items'] instanceof InitiativeCollection;
//        return $data instanceof InitiativeCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
