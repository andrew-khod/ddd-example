<?php

namespace App\Identity\UI\Web\Customer\Normalizer;

use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\DeletedCustomer;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CustomerNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public string $baseUrl;
    private string $customerPhotoPath;
    private S3Client $s3Client;
    private string $awsS3Bucket;

    public function __construct(RequestStack $requestStack,
                                string $customerPhotoPath,
                                string $awsS3Bucket,
                                S3Client $s3Client)
    {
        $request = $requestStack->getCurrentRequest();

        if (!$request) {
            return;
        }

        $this->baseUrl = $request->getSchemeAndHttpHost();
        $this->customerPhotoPath = $customerPhotoPath;
        $this->s3Client = $s3Client;
        $this->awsS3Bucket = $awsS3Bucket;
    }

    /**
     * @param Customer $customer
     * @param null             $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($customer, $format = null, array $context = []): ?array
    {
        if ($customer instanceof DeletedCustomer) {
            return parent::normalize(null);
        }

        return parent::normalize([
            'id' => (string) $customer->id(),
            'email' => (string) $customer->email(),
            'username' => (string) $customer->username(),
            'name' => $customer->username()
                ? (string) $customer->username()
                : null,
            'birthday' => $customer->birthday()
                ? (string) $customer->birthday()
                : null,
            'postal' => $customer->postal()
                ? (string) $customer->postal()
                : null,
            'gender' => $customer->gender()
                ? (string) $customer->gender()
                : null,
            'photo' => $customer->photo()
                ? $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->customerPhotoPath, $customer->photo()))
                : null,
            'created' => $customer->created(),
//            'photo' => $customer->photo()
//                ? $this->baseUrl.$this->customerPhotoPath.DIRECTORY_SEPARATOR.$customer->photo()
//                : null,
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
