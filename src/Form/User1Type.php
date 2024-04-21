<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', null, ['attr' => ['class' => 'form-control']])
            ->add('firstName', null, ['attr' => ['class' => 'form-control']])
            ->add('username', null, ['attr' => ['class' => 'form-control']])
            ->add('emailAdress', null, ['attr' => ['class' => 'form-control']])
            ->add('password', null, ['attr' => ['class' => 'form-control']])
            ->add('status', null, ['attr' => ['class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
