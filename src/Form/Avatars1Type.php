<?php

namespace App\Form;

use App\Entity\Avatars;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class Avatars1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('user_ID', null ,[
            //     "label" => "Nom d'utilisateur",
            //     'attr'=>[
            //         'disabled'=>'disabled'
            //     ] 
            //     ] )

            ->add('pseudo',TextType::class , [
                'label'=>'Pseudo',   
                'attr'=> ['maxlength'=> 101],
                'constraints' => [
                    new Length([
                        'maxMessage' => 'ton pseudo ne doit pas dépasser {{ limit }} caractères, tu as un caractère de trop ',
                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ]              
            ])

            ->add('cat', TextareaType::class , [
                'required' => false,
                'label'=>"Introduction d'accueil",
                'attr'=> ['maxlength'=> 201],
                'constraints' => [
                    new Length([
                        'maxMessage' => 'ton texte ne doit pas dépasser {{ limit }} charactères, tu as un caractère de trop ! ',
                        // max length allowed by Symfony for security reasons
                        'max' => 200,
                    ]),
                ]  
            ],
            )

            ->add('description',CKEditorType::class , [
                'label'=>'Description',  
            ])

            ->add('picture',FileType::class, [
                'data_class' => null,
                'label'=> "Fichier image (jpg, png)",
            ])

            ->add('options', ChoiceType::class, [
                    'choices'  => [
                        'Sofrologie' => 'Sofrologie',
                        'Bien-être'  => 'Bien-être',
                        'Méditation'    => 'Méditation',
                        'Médecine holistique'    => 'Soins',
                        'Chamanisme'    => 'Chamanisme',
                        'Musique zen'    => 'Musique zen'
                    ], 
                    'multiple'=>true,
                    'expanded'=>true, 
                    'label'=>'Hobbis/passions',             
                ])

            ->add('age', IntegerType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avatars::class,
        ]);
    }
}
