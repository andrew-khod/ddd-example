<?php

namespace App\Identity\UI\Dashboard\Customer;

use App\Identity\Domain\Customer\BannedCustomer;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\NotActivatedCustomer;
use App\Identity\UI\Web\Customer\Normalizer\CustomerNormalizer;
use App\Identity\UI\Web\Customer\Normalizer\NotActivatedCustomerNormalizer;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomerCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private CustomerNormalizer $customerNormalizer;
    private NotActivatedCustomerNormalizer $notActivatedCustomerNormalizer;

    public function __construct(CustomerNormalizer $customerNormalizer, NotActivatedCustomerNormalizer $notActivatedCustomerNormalizer)
    {
        $this->customerNormalizer = $customerNormalizer;
        $this->notActivatedCustomerNormalizer = $notActivatedCustomerNormalizer;
    }

    /**
     * @param CustomerCollectionPayload $customers
     * @param null                 $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($customers, $format = null, array $context = []): array
    {
        $response = [];

        foreach ($customers->get()->toArray() as $customer) {
            if ($customer instanceof Customer) {
                $c = $this->customerNormalizer
                    ->setWrapped(false)
                    ->normalize($customer, $format, $context);
                $c['status'] = 'active';
                $response[] = $c;
            }

            if ($customer instanceof NotActivatedCustomer) {
                $c = $this->notActivatedCustomerNormalizer
                    ->setWrapped(false)
                    ->normalize($customer, $format, $context);
                $c['status'] = 'inactive';
                $response[] = $c;
            }

            if ($customer instanceof BannedCustomer) {
                $c = $this->customerNormalizer
                    ->setWrapped(false)
                    ->normalize($customer, $format, $context);
                $c['status'] = 'banned';
                $response[] = $c;
            }
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CustomerCollectionPayload;
//        return $data instanceof DashboardPayload && $data->get() instanceof CustomerCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}