<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\Contact;
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
     * @param ContactCollectionPayload $contacts
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($contacts, $format = null, array $context = []): array
    {
        $response = [];

        /** @var Contact $contact */
        foreach ($contacts->get()->toArray() as $contact) {
            $response[(string) $contact->id()] = $this->normalizer
                ->setWrapped(false)
                ->normalize($contact, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ContactCollectionPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
