<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'attr'=> ['maxlength'=> 100],
                'constraints' => [
                    new Length([
                        'maxMessage' => 'ton titre ne doit pas dépasser {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ]
            ])
            ->add('comment')
            ->add('is_verified')
            // ->add('created')
            ->add('avatar_ID')
            ->add('article_ID')
            ->add('submit', SubmitType::class, [
                'label'=>"envoyer le commentaire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
