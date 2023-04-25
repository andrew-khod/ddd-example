<?php

namespace App\Identity\UI\Web\Customer\Normalizer;

use App\Identity\UI\Web\Customer\Controller\NotificationsPayload;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class NotificationsNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
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
     * @param NotificationsPayload $notifications
     * @param null             $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($notifications, $format = null, array $context = []): ?array
    {
        $notifications = $notifications->notifications();

        array_walk_recursive($notifications, function (&$item, $key) {
            if ($key === 'photo' && $item) {
                $item = $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->customerPhotoPath, $item));
            }
        });

        return parent::normalize($notifications, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof NotificationsPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
