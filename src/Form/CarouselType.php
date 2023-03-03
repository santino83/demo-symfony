<?php

namespace App\Form;

use App\Entity\Carousel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarouselType extends AbstractType
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
            ->add('position')
            ->add('fade')
            ->add('autoplay')
            ->add('interval', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 100
                ],
                'required' => false
            ])
            ->add('indicators')
            ->add('controls')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carousel::class,
        ]);
    }
}
