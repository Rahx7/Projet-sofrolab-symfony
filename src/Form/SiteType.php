<?php

namespace App\Form;

use App\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intro1')
            ->add('title1')
            ->add('intro', CKEditorType::class)
            ->add('subtitle1')
            ->add('subtitle2')
            ->add('subtitle3')
            ->add('subtitle11')
            ->add('subtitle22')
            ->add('subtitle33')
            ->add('text1', CKEditorType::class)
            ->add('text2',CKEditorType::class)
            ->add('text3',CKEditorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
