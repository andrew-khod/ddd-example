<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\CookiesPolicy;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use HTMLPurifier;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CookiesPolicyNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ActiveLanguage $activeLanguage;
    private HTMLPurifier $purifier;

    public function __construct(ActiveLanguage $activeLanguage, HTMLPurifier $purifier)
    {
        $this->activeLanguage = $activeLanguage;
        $this->purifier = $purifier;
    }

    /**
     * @param CookiesPolicy $contact
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($cookies, $format = null, array $context = []): array
    {
        $response = [
            'title' => $cookies->title(),
            'description' => $this->purifier->purify($cookies->description()),
        ];

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CookiesPolicy;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
