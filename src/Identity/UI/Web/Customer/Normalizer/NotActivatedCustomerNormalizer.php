<?php

namespace App\Identity\UI\Web\Customer\Normalizer;

use App\Identity\Domain\Customer\NotActivatedCustomer;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class NotActivatedCustomerNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param NotActivatedCustomer $customer
     * @param null                 $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($customer, $format = null, array $context = []): array
    {
        return parent::normalize([
            'id' => (string) $customer->id(),
            'email' => (string) $customer->email(),
            'username' => null,
            'gender' => null,
            'photo' => null,
            'created' => $customer->created(),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof NotActivatedCustomer;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
