<?php

namespace App\Initiative\UI\Dashboard\Initiative\Normalizer;

use App\Initiative\UI\Dashboard\Initiative\InitiativeCollectionPayload;
use App\Initiative\UI\Dashboard\Initiative\InitiativePayload;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InitiativeCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private InitiativeNormalizer $initiativeNormalizer;

    public function __construct(InitiativeNormalizer $initiativeNormalizer)
    {
        $this->initiativeNormalizer = $initiativeNormalizer;
    }

    /**
     * @param InitiativeCollectionPayload $initiatives
     * @param null                 $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($initiatives, $format = null, array $context = []): array
    {
        $response = [];

        foreach ($initiatives->get()->toArray() as $initiative) {
            $response[] = $this->initiativeNormalizer
                ->setWrapped(false)
                ->normalize(new InitiativePayload($initiative), $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof InitiativeCollectionPayload;
//        return $data instanceof DashboardPayload && $data->get() instanceof InitiativeCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
