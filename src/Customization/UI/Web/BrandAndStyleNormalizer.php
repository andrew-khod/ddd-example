<?php

namespace App\Customization\UI\Web;

use App\Shared\Domain\Language;
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
//    private AllLanguageQuery $allLanguageQuery;

    public function __construct(
                                string                  $headerImagePath,
                                string                  $logoImagePath,
                                string                  $awsS3Bucket,
                                S3Client                $s3Client,
//                                AllLanguageQuery $allLanguageQuery
    )
    {
        $this->headerImagePath = $headerImagePath;
        $this->logoImagePath = $logoImagePath;
        $this->awsS3Bucket = $awsS3Bucket;
        $this->s3Client = $s3Client;
//        $this->allLanguageQuery = $allLanguageQuery;
    }

    /**
     * @param KnowledgePayload $payload
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($payload, $format = null, array $context = []): array
    {
        $company = $payload->company();
        $languages = $payload->languages();
        return parent::normalize([
            'name' => (string) $company->name(),
            'color' => $company->color(),
            'footer' => $company->footer(),
            'header' => $company->header() ? $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->headerImagePath, $company->header())) : null,
            'logo' => $company->logo() ? $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->logoImagePath, $company->logo())) : null,
            'logo_second' => $company->secondLogo() ? $this->s3Client->getObjectUrl($this->awsS3Bucket, sprintf('%s/%s', $this->logoImagePath, $company->secondLogo())) : null,
            'languages' => array_map(fn(Language $language) => (string) $language, $languages),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof KnowledgePayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
