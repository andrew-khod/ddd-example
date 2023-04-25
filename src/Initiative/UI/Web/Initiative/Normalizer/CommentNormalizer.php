<?php

namespace App\Initiative\UI\Web\Initiative\Normalizer;

use App\Identity\UI\Web\Customer\Normalizer\CustomerNormalizer;
use App\Initiative\Domain\Comment\Comment;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CommentNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private CustomerNormalizer $customerNormalizer;

    public function __construct(CustomerNormalizer $customerNormalizer)
    {
        $this->customerNormalizer = $customerNormalizer;
    }

    /**
     * @param Comment $comment
     * @param null       $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($comment, $format = null, array $context = []): array
    {
        // TODO use specific AuthorNormalizer and ParticipantCollectionNormalizer to define & present different Customer contexts
        return parent::normalize([
            'id' => (string) $comment->id(),
            'text' => (string) $comment,
            'author' => $this->customerNormalizer
                ->setWrapped(false)
                ->normalize($comment->author(), $format, $context),
            'posted' => $comment->posted(),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Comment;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
