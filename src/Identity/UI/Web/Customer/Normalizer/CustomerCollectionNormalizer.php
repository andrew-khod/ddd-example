<?php

namespace App\Identity\UI\Web\Customer\Normalizer;

use App\Identity\Domain\Customer\CustomerCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CustomerCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private CustomerNormalizer $customerNormalizer;

    public function __construct(CustomerNormalizer $customerNormalizer)
    {
        $this->customerNormalizer = $customerNormalizer;
    }

    /**
     * @param CustomerCollection $customer
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($customers, $format = null, array $context = []): ?array
    {
        $response = [];

        foreach ($customers->toArray() as $customer) {
            $response[] = $this->customerNormalizer
                ->setWrapped(false)
                ->normalize($customer, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CustomerCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
