<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\Custom\ExtraTextType;
use App\Form\Transformer\TagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {
                /** @var Post $post */
                $post = $event->getData();
                $form = $event->getForm();

                if(!$post) {
                    return;
                }

                if($post->getState() == 'published') {
                    $form->add('publishedAt');
                }
            })
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Post $post */
                $post = $event->getData();

                if($post->getState() == 'published' && $post->getPublishedAt() == null) {
                    $post->setPublishedAt(new \DateTimeImmutable());
                    $event->setData($post);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'required' => false
        ]);
    }
}
