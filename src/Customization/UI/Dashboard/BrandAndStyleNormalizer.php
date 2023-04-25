<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Aws\S3\S3Client;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BrandAndStyleNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private string $headerImagePath;
    private string $awsS3Bucket;
    private S3Client $s3Client;
    private string $logoImagePath;

    public function __construct(string $headerImagePath,
                                string $logoImagePath,
                                string $awsS3Bucket,
                                S3Client $s3Client)
    {
        $this->headerImagePath = $headerImagePath;
        $this->awsS3Bucket = $awsS3Bucket;
        $this->s3Client = $s3Client;
        $this->logoImagePath = $logoImagePath;
    }

    /**
     * @param BrandAndStylePayload $payload
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($payload, $format = null, array $context = []): array
    {
        $company = $payload->get();

        return parent::normalize([
            'name' => (string) $company->name(),
            'alias' => (string) $company->alias(),
            'url' => (string) $company->url(),
            'color' => (string) $company->color(),
            'footer' => $company->footer(),
            'header' => $company->header() ? $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->headerImagePath, $company->header())) : null,
            'logo' => $company->logo() ? $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->logoImagePath, $company->logo())) : null,
            'logo_second' => $company->secondLogo() ? $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->logoImagePath, $company->secondLogo())) : null,
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof BrandAndStylePayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
