<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off'
                ],
            ])
            ->add('cover', FileType::class, [
                'label' => 'imagen',
                'mapped' => false,
                ])
            ->add('GameSystem', ChoiceType::class, [
                'choices' => [
                    'D&D' => 'D&D', 
                    'Fate' => 'Fate',

                ],
            ])
            ->add('submit', SubmitType::class,
            [
                'label' => 'Crear partida'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
