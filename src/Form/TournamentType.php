<?php

namespace App\Form;

use App\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tournamentName', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('startDate', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('endDate', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('location', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('maxParticipants', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('game', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('organizer', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('winner', null, [
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
