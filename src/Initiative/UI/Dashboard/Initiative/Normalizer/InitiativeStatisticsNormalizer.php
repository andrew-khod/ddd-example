<?php

declare(strict_types=1);

namespace App\Initiative\UI\Dashboard\Initiative\Normalizer;

use App\Initiative\Domain\Initiative\InitiativeStatistics;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InitiativeStatisticsNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param InitiativeStatistics $statistics
     * @param null       $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($statistics, $format = null, array $context = []): array
    {
        // TODO use specific AuthorNormalizer and ParticipantCollectionNormalizer to define & present different Customer contexts
        return parent::normalize([
            'initiatives' => $statistics->initiatives(),
            'last_initiatives' => $statistics->lastWeekInitiatives(),
            'comments' => $statistics->comments(),
            'last_comments' => $statistics->lastWeekComments(),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof InitiativeStatistics;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}