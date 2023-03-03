<?php

namespace App\Form;

use App\Entity\Thematic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThematicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'required' => true
            ])
            ->add('page', ChoiceType::class, [
                'choices' => [
                    'Home Page' => 'homepage',
                    'Other Page' => 'other'
                ],
                'required' => 'true',
                'empty_data' => 'homepage'
            ])
            ->add('position', null, ['required' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Thematic::class,
        ]);
    }
}
