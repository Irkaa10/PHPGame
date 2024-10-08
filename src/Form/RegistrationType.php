<?php

namespace App\Form;

use App\Entity\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('registrationDate', null, ['attr' => ['class' => 'form-control']])
            ->add('status', null, ['attr' => ['class' => 'form-control']])
            ->add('player', null, ['attr' => ['class' => 'form-control']])
            ->add('tournament', null, ['attr' => ['class' => 'form-control']])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
        ]);
    }
}
