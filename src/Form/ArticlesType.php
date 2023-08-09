<?php

namespace App\Form;

use App\Entity\Avatars;
use App\Entity\Articles;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\Length;


class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class , [
                'label'=>'Titre',
                'attr'=> ['maxlength'=> 251],
                'constraints' => [
                    new Length([
                        'maxMessage' => 'ton titre ne doit pas dépasser {{ limit }} caractères, tu as juste un caractère de trop, supp 1X',
                        // max length allowed by Symfony for security reasons
                        'max' => 250,
                    ]),
                ]               
            ])
            ->add('sub_title',TextType::class , [
                'label'=>'Sous titre',
                'attr'=> ['maxlength'=> 251],
                'constraints' => [
                    new Length([
                        'maxMessage' => 'ton sous-titre ne doit pas dépasser {{ limit }} charactères, tu as un caractère de trop',
                        // max length allowed by Symfony for security reasons
                        'max' => 250,
                    ]),
                ] 
                
            ])
            ->add('intro',TextareaType::class , [
                'label'=>'Intro',
                
            ])
            ->add('description', CKEditorType::class , [
                'label'=>'Déscription',
                
            ])
            ->add('src',FileType::class, [
                'required' => false,
                'label'=> "fichier audio (mp3, mp4)"
            ])

            ->add('infos', TextType::class , [
                'required' => false,
                'label'=>'Informations complémentaires',
                ])

            ->add('img',FileType::class, [
                'label'=> "fichier image (jpg, png)",
            ])

            ->add('cat', ChoiceType::class, [
                    'choices'  => [
                        'Sophrologie' => 'Sophrologie',
                        'Bien-être'  => 'bien-etre',
                        'Méditation'    => 'meditation',
                        'medecine holistique'    => 'medecine',
                    ], 
                    'multiple'=>true,
                    'expanded'=>true, 
                    'label'=>'Domaines',             
                ])

            ->add('level',ChoiceType::class, [
                'choices'  => [
                    'Débutant' => 'debutant',
                    'Moyen'  => 'moyen',
                    'Expert'    => 'expert',
                ], 
                'multiple'=>true,
                'expanded'=>true, 
                'label'=>'Difficulté',
                ])

            // ->add('avatar_ID', EntityType::class,[
            //     'class'=> Avatars::class,
            //     'label'=>'Utilisateur',
            //     // 'choice_label'=>'Utilisateur'
            //     ]) // a enlever

            ->add('submit', SubmitType::class, [
                    'label'=>'Ajouter article',
                    'attr'=>[
                        'class'=>'ui button primary'
                    ]
                    
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
