<?php

namespace App\Customization\UI\Web;

use App\Customization\Domain\Question;
use App\Customization\Domain\QuestionCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class QuestionCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private QuestionNormalizer $normalizer;

    public function __construct(QuestionNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param QuestionCollection $questions
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($questions, $format = null, array $context = []): array
    {
        $response = [];

        /** @var Question $question */
        foreach ($questions->toArray() as $question) {
            $response[] = $this->normalizer
                ->setWrapped(false)
                ->normalize($question, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof QuestionCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
