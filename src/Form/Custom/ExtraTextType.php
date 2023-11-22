<?php

namespace App\Form\Custom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtraTextType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['text_before'] = $options['text_before'];
        $view->vars['text_after'] = $options['text_after'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // configureOptions à l'aide de OptionsResolver permet de définir des nouvelles options pour un champs de formulaire,
        // de leurs définir un type de valeur ainsi que leurs valeurs par défaut

        // $resolver->setDefined('text_before');
        // setDefaults permet de déclarer une options et de définir la valeur par défaut
        $resolver->setDefaults([
            'text_before' => null,
            'text_after' => null
        ]);

        $resolver
            ->setAllowedTypes('text_before', ['null', 'string']) // accepte la valeur null ou une chaine de caractères
            ->setAllowedTypes('text_after', ['null', 'string'])
        ;
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }
}
