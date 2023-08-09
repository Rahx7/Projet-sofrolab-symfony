<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticlesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Articles::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [

            //IdField::new('id'),
            AssociationField::new('avatar_ID',"l'avatar"),
            
            TextField::new('title', 'Titre'),
            TextField::new('sub_title','sous titre'),
            TextEditorField::new('intro','Intro'),
            TextEditorField::new('description','déscription'),

            ImageField::new('src','le fichier audio')
                ->setBasePath('upload/')
                ->setUploadDir('public/upload/')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),

            TextField::new('infos','les infos en plus'),
            
            ImageField::new('img','la photo')
                ->setBasePath('upload/')
                ->setUploadDir('public/upload/')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),

            ArrayField::new('cat', 'la catégorie/thematique'),

            ArrayField::new('level','la dificulté'),

            BooleanField::new('is_verified'),
            DateField::new('created', 'date de création'),

            AssociationField::new('comments',"les commentaires"),
            AssociationField::new('notes',"les notes"),


        ];
    }
    /**/
}
