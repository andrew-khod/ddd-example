<?php

namespace App\Customization\UI\Web;

use App\Customization\Domain\Contact;
use App\Customization\Domain\ContactTranslation;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ContactNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ActiveLanguage $activeLanguage;

    public function __construct(ActiveLanguage $activeLanguage)
    {
        $this->activeLanguage = $activeLanguage;
    }

    /**
     * @param Contact $contact
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($contact, $format = null, array $context = []): array
    {
        $response = array_map(
            fn(ContactTranslation $translation) => [
                'type' => $translation->type(),
                'value' => $translation->value(),
            ],
            $contact->translations($this->activeLanguage)->toArray(),
        );

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Contact;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
