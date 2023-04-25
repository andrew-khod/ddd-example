<?php

namespace App\Initiative\UI\Web\Initiative\Normalizer;

use App\Initiative\Domain\Comment\CommentCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CommentCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private CommentNormalizer $commentNormalizer;

    public function __construct(CommentNormalizer $commentNormalizer)
    {
        $this->commentNormalizer = $commentNormalizer;
    }

    /**
     * @param CommentCollection $comments
     * @param null                 $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($comments, $format = null, array $context = []): array
    {
        $response = [];

        foreach ($comments->toArray() as $comment) {
            $response[] = $this->commentNormalizer
                ->setWrapped(false)
                ->normalize($comment, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CommentCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
