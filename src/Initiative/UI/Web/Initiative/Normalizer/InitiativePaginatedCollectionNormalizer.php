<?php

namespace App\Initiative\UI\Web\Initiative\Normalizer;

use App\Initiative\UI\Web\Initiative\Controller\InitiativeListPaginatedPayload;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InitiativePaginatedCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private InitiativeBriefingNormalizer $initiativeNormalizer;

    public function __construct(InitiativeBriefingNormalizer $initiativeNormalizer)
    {
        $this->initiativeNormalizer = $initiativeNormalizer;
    }

    /**
     * @param InitiativeListPaginatedPayload $initiatives
     * @param null                 $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($initiatives, $format = null, array $context = []): array
    {
        $initiatives = $initiatives->initiatives();
        $total = $initiatives['total'];
        $pages = $initiatives['pages'];
        $initiatives = $initiatives['items'];
        // todo move pages property along with data prop
        $response = [
            'pages' => $pages,
            'total' => $total,
            'items' => [],
        ];

        foreach ($initiatives->toArray() as $initiative) {
            $response['items'][] = $this->initiativeNormalizer
                ->setWrapped(false)
                ->normalize($initiative, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof InitiativeListPaginatedPayload;
//        return is_array($data) && key_exists('items', $data) && $data['items'] instanceof InitiativeCollection;
//        return $data instanceof InitiativeCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
