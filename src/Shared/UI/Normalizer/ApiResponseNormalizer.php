<?php

namespace App\Shared\UI\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    // TODO use decorators instead of bool flag
    private bool $isWrapped = true;

    public function setWrapped(bool $isWrapped): self
    {
        $this->isWrapped = $isWrapped;

        return $this;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $object;

        if ($this->isWrapped) {
            $data = [
                'data' => $object,
            ];
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return is_string($data);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
