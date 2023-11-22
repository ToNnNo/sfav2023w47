<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\Custom\ExtraTextType;
use App\Form\Transformer\TagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', ExtraTextType::class, [
                'text_before' => '--',
            ])
            ->add('content')
            ->add('state')
            ->add('tags', options: [
                'help' => "Les tags doivent être séparés par une virgule"
            ])
        ;

        $builder->get('tags')
            ->addViewTransformer(new TagsTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'required' => false
        ]);
    }
}
