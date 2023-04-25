<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\TermsOfUse;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use HTMLPurifier;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TermsOfUseNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ActiveLanguage $activeLanguage;
    private HTMLPurifier $purifier;

    public function __construct(ActiveLanguage $activeLanguage, HTMLPurifier $purifier)
    {
        $this->activeLanguage = $activeLanguage;
        $this->purifier = $purifier;
    }

    /**
     * @param TermsOfUse $termsOfUse
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($termsOfUse, $format = null, array $context = []): array
    {
        $response = [
            'title' => $termsOfUse->title(),
            'description' => $this->purifier->purify($termsOfUse->description()),
        ];

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof TermsOfUse;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
