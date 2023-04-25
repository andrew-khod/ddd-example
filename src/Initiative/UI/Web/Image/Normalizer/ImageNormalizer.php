<?php

namespace App\Initiative\UI\Web\Image\Normalizer;

use App\Initiative\Domain\Image\Image;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ImageNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public string $baseUrl;
    private string $initiativeImagesPath;
    private S3Client $s3Client;
    private string $awsS3Bucket;

    public function __construct(RequestStack $requestStack,
                                string $initiativeImagesPath,
                                string $awsS3Bucket,
                                S3Client $s3Client)
    {
        $request = $requestStack->getCurrentRequest();

        if (!$request) {
            return;
        }

        $this->baseUrl = $request->getSchemeAndHttpHost();
        $this->initiativeImagesPath = $initiativeImagesPath;
        $this->s3Client = $s3Client;
        $this->awsS3Bucket = $awsS3Bucket;
    }

    /**
     * @param Image $image
     * @param null  $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($image, $format = null, array $context = []): array
    {
        return parent::normalize([
            'id' => (string) $image->id(),
            'url' => $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->initiativeImagesPath, $image->pathname())),

// todo            'url' => new ImageS3Normalizer()

//            'url' => $this->baseUrl
//                .$this->initiativeImagesPath
//                .DIRECTORY_SEPARATOR
//                .$image->pathname(),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Image;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
