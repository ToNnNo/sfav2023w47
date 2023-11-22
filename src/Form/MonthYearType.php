<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthYearType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('month', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Janvier' => 1, 'Février' => 2, "Mars" => 3, "Avril" => 4,
                    'Mai' => 5, 'Juin' => 6, "Juillet" => 7, "Aout" => 8,
                    'Septembre' => 9, 'Octobre' => 10, "Novembre" => 11, "Décembre" => 12
                ]
            ])
            ->add('year', ChoiceType::class, [
                'label' => false,
                'choices' => array_combine(range(2018, 2033), range(2018, 2033))
            ])
            ->setDataMapper($this)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        // si la $viewData est vide (null)
        if( $viewData == null ) {
            return;
        }

        // si la $viewData n'est une date (DateTime)
        if( !$viewData instanceof \DateTime ) {
            throw new UnexpectedTypeException($viewData, \DateTime::class);
        }

        // transforme un iterator (Traversable) en tableau
        /** @var FormInterface[] $forms_array */
        $forms_array = iterator_to_array($forms);

        $forms_array['month']->setData($viewData->format('m'));
        $forms_array['year']->setData($viewData->format('Y'));
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        /** @var FormInterface[] $forms_array */
        $forms_array = iterator_to_array($forms);

        $month = $forms_array['month']->getData();
        $year = $forms_array['year']->getData();

        $viewData = (new \DateTime())->setDate($year, $month, 1);
    }
}
