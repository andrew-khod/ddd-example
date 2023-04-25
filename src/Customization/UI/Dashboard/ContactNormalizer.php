<?php

namespace App\Customization\UI\Dashboard;

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
        $response = [];

        /** @var ContactTranslation $translation */
        foreach($contact->translations()->toArray() as $translation) {
            $response[$translation->language()][] = [
                'type' => $translation->type(),
                'value' => $translation->value(),
            ];
        }

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
