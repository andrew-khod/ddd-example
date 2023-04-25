<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\Question;
use App\Customization\Domain\QuestionTranslation;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class QuestionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ActiveLanguage $activeLanguage;

    public function __construct(ActiveLanguage $activeLanguage)
    {
        $this->activeLanguage = $activeLanguage;
    }

    /**
     * @param Question $question
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($question, $format = null, array $context = []): array
    {
        $response = [];

        /** @var QuestionTranslation $translation */
        foreach($question->translations()->toArray() as $translation) {
            $response[$translation->language()] = [
                'question' => $translation->question(),
                'answer' => $translation->answer(),
            ];
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Question;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
