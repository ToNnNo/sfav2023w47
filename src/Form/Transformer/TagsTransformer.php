<?php

namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function transform(mixed $arrayToString)
    {
        return implode(', ', $arrayToString ?? []);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $stringToArray)
    {
        return array_map('trim', explode(',', $stringToArray));
    }
}
