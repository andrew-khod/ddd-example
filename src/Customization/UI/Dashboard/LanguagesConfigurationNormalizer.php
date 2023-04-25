<?php

namespace App\Customization\UI\Dashboard;

use App\Shared\Domain\AvailableLanguage;
use App\Shared\Domain\Language;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Aws\S3\S3Client;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LanguagesConfigurationNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
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
     * @param LanguageConfigurationPayload $payload
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($payload, $format = null, array $context = []): array
    {
        return parent::normalize([
            'assigned' => array_map(fn(Language $language) => [
                'id' => (string) $language->id(),
                'name' => $language->name(),
            ], $payload->assignedLanguages()),
            'available' => array_map(fn(AvailableLanguage $language) => (string) $language->name(), $payload->availableLanguages()),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof LanguageConfigurationPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
