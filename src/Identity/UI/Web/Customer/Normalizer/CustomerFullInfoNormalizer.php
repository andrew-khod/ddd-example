<?php

namespace App\Identity\UI\Web\Customer\Normalizer;

use App\Identity\Domain\Customer\Customer;
use App\Initiative\UI\Web\Initiative\Normalizer\InitiativeCollectionForProfileViewNormalizer;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CustomerFullInfoNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private CustomerNormalizer $customerNormalizer;
    private InitiativeCollectionForProfileViewNormalizer $initiativeCollectionNormalizer;

    public function __construct(CustomerNormalizer             $customerNormalizer,
                                InitiativeCollectionForProfileViewNormalizer $initiativeCollectionNormalizer)
    {
        $this->customerNormalizer = $customerNormalizer;
        $this->initiativeCollectionNormalizer = $initiativeCollectionNormalizer;
    }

    /**
     * @param Customer $customer
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($customer, $format = null, array $context = []): ?array
    {
        $this->initiativeCollectionNormalizer->setWrapped(false);
        return parent::normalize([
            'customer' => $this->customerNormalizer
                ->setWrapped(false)
                ->normalize($customer),
            'initiatives' => [
                // TODO get rid of these props, create respective endpoints instead
                'joined' => $this->initiativeCollectionNormalizer->normalize($customer->participation(), $format, $context),
                'followed' => $this->initiativeCollectionNormalizer->normalize($customer->following(), $format, $context),
                'added' => $this->initiativeCollectionNormalizer->normalize($customer->initiatives(), $format, $context),
                'archived' => $this->initiativeCollectionNormalizer->normalize($customer->initiatives()->archived(), $format, $context),
                'favourites' => $this->initiativeCollectionNormalizer->normalize($customer->favourites(), $format, $context),
//                'joined' => $customer->participation()->toIDs(true),
//                'followed' => $customer->following()->toIDs(true),
//                'added' => $customer->initiatives()->toIDs(true),
//                'archived' => $customer
//                    ->initiatives()
//                    ->archived()
//                    ->toIDs(true),
            ],
            'comments' => [
                'added' => $customer->comments()->count(),
            ],
            'language' => $customer->activeLanguage()?->name(),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Customer;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
