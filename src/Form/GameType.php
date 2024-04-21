<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gameDate', null, ['attr' => ['class' => 'form-control']])
            ->add('scorePlayer1', null, ['attr' => ['class' => 'form-control']])
            ->add('scorePlayer2', null, ['attr' => ['class' => 'form-control']])
            ->add('status', null, ['attr' => ['class' => 'form-control']])
            ->add('player1', null, ['attr' => ['class' => 'form-control']])
            ->add('player2', null, ['attr' => ['class' => 'form-control']])
            ->add('tournament', null, ['attr' => ['class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
