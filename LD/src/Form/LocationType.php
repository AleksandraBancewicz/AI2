<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Poprawny import
use Symfony\Component\Form\Extension\Core\Type\NumberType; // Dodanie NumberType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter city name',
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'Spain' => 'ES',
                    'Italy' => 'IT',
                    'United Kingdom' => 'GB',
                    'United States' => 'US',
                ],
                'placeholder' => 'Choose country',
            ])
            ->add('latitude', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Enter latitude',
                ],
                'constraints' => [
                    new Type(['type' => 'numeric', 'message' => 'Latitude must be a valid number']),
                    new Range(['min' => -90, 'max' => 90, 'notInRangeMessage' => 'Latitude must be between -90 and 90']),
                ],
            ])
            ->add('longitude', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Enter longitude',
                ],
                'constraints' => [
                    new Type(['type' => 'numeric', 'message' => 'Longitude must be a valid number']),
                    new Range(['min' => -180, 'max' => 180, 'notInRangeMessage' => 'Longitude must be between -180 and 180']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}