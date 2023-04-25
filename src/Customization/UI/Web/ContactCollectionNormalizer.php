<?php

namespace App\Customization\UI\Web;

use App\Customization\Domain\Contact;
use App\Customization\Domain\ContactCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ContactCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ContactNormalizer $normalizer;

    public function __construct(ContactNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param ContactCollection $contacts
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($contacts, $format = null, array $context = []): array
    {
        $response = [];

        /** @var Contact $contact */
        foreach ($contacts->toArray() as $contact) {
            $response[] = $this->normalizer
                ->setWrapped(false)
                ->normalize($contact, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ContactCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
