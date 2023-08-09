<?php

namespace App\Form;

use App\Entity\Avatars;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture')
            ->add('pseudo')
            ->add('description')
            ->add('options')
            ->add('age')
            ->add('cat')
            ->add('is_verified')
            ->add('user_ID')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avatars::class,
        ]);
    }
}
