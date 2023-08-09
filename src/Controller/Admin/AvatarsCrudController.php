<?php

namespace App\Controller\Admin;

use App\Entity\Avatars;
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
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AvatarsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avatars::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            ImageField::new('picture','la photo avatar')
                ->setBasePath('upload/')
                ->setUploadDir('public/upload/')
                // ->setFormType(FileUploadType::class)
                // ->setFormTypeOption('allow_delete', false)
                // ->setFormTypeOption('upload_delete', function(File $file) { dump("coucou"); die(); })
                // ->setRequired(false),
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
            TextField::new('pseudo', 'Pseudo'),
            TextEditorField::new('description','déscription'),
            ArrayField::new('options','les infos en plus'),
            IntegerField::new('age','Age'),
            TextField::new('cat', 'la catégorie/thematique favorite'),
                                
            BooleanField::new('is_verified'),
            AssociationField::new('user_ID',"l'utilisateur"),
            AssociationField::new('articles',"les articles"),
            AssociationField::new('comments',"les commentaires"),
            AssociationField::new('infosAvatar',"les infos avatar ajouté"),
            AssociationField::new('friends',"les amis"),
            AssociationField::new('messages',"les messages"),
            AssociationField::new('notes',"les notes"),
      
            
        ];
    }
    
}
