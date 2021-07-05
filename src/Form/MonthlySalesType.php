<?php

namespace App\Form;

use App\Service\DateService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthlySalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monthAvailable', ChoiceType::class, [
                'choices' =>$options['data'],
                'choice_label' => function ($choice, $key, $value) {
                    return DateService::monthYeartoFrench($value);
                },
                'label' => false
            ])
            ->add('submit', SubmitType::class, ['label' => 'Afficher ventes et objectifs',
                'attr' => [
                    'class' => 'btn btn-primary btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
