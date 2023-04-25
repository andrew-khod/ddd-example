<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\Rule;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RuleCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private RuleNormalizer $normalizer;

    public function __construct(RuleNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param RuleCollectionPayload $rules
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($rules, $format = null, array $context = []): array
    {
        $response = [];

        /** @var Rule $rule */
        foreach ($rules->get()->toArray() as $rule) {
            $response[(string) $rule->language()] = $this->normalizer
                ->setWrapped(false)
                ->normalize($rule, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof RuleCollectionPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
