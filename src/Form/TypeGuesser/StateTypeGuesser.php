<?php

namespace App\Form\TypeGuesser;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;

class StateTypeGuesser implements FormTypeGuesserInterface
{

    /**
     * @inheritDoc
     */
    public function guessType(string $class, string $property): ?TypeGuess
    {
        if (!class_exists($class) || !property_exists($class, $property)){
            return null;
        }

        if ($property != "state") {
           return null;
        }

        return new TypeGuess(ChoiceType::class, [
            'choices' => [
                'En rédaction' => 'draft',
                'Publié' => 'published',
                'Non Publié' => 'unpublished',
                'Archivé' => 'archived'
            ]
        ], Guess::MEDIUM_CONFIDENCE);
    }

    /**
     * @inheritDoc
     */
    public function guessRequired(string $class, string $property): ?ValueGuess
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function guessMaxLength(string $class, string $property): ?ValueGuess
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function guessPattern(string $class, string $property): ?ValueGuess
    {
        return null;
    }
}
