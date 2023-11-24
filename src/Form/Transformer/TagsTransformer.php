<?php

namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function transform(mixed $arrayToString): mixed
    {
        return implode(', ', $arrayToString ?? []);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $stringToArray): mixed
    {
        return array_map('trim', explode(',', $stringToArray));
    }
}
