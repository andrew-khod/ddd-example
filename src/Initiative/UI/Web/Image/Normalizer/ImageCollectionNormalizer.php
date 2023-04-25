<?php

namespace App\Initiative\UI\Web\Image\Normalizer;

use App\Initiative\Domain\Image\ImageCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ImageCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ImageNormalizer $imageNormalizer;

    public function __construct(ImageNormalizer $imageNormalizer)
    {
        $this->imageNormalizer = $imageNormalizer;
    }

    /**
     * @param ImageCollection $images
     * @param null            $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($images, $format = null, array $context = []): array
    {
        $response = [];

        foreach ($images->toArray() as $image) {
            $response[] = $this->imageNormalizer
                ->setWrapped(false)
                ->normalize($image, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ImageCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
