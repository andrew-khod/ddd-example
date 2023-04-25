<?php

namespace App\Customization\UI\Web;

use App\Customization\Domain\Question;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use HTMLPurifier;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class QuestionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ActiveLanguage $activeLanguage;
    private HTMLPurifier $purifier;

    public function __construct(ActiveLanguage $activeLanguage, HtmlPurifier $purifier)
    {
        $this->activeLanguage = $activeLanguage;
        $this->purifier = $purifier;
    }

    /**
     * @param Question $question
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($question, $format = null, array $context = []): array
    {
        $response = [
            'question' => $question->question($this->activeLanguage),
            'answer' => $this->purifier->purify($question->answer($this->activeLanguage)),
//            'answer' => strip_tags($question->answer($this->activeLanguage), [
//                'b',
//            ]),
        ];

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
