<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comments::class;
    }

    /**/
    public function configureFields(string $pageName): iterable
    {
        return [
            
            //IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('comment'),
            BooleanField::new('is_verified'),
            DateField::new('created', 'date de création'),
            AssociationField::new('avatar_ID',"l'utilisateur"),
            AssociationField::new('article_ID',"l'article"),

        ];
    }
    
}
