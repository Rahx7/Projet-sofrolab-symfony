<?php

namespace App\Controller\Admin;

use App\Entity\Notes;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NotesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Notes::class;
    }

    /**/

    public function configureFields(string $pageName): iterable
    {
        return [

            //IdField::new('id'),
            AssociationField::new('avatar_ID',"l'avatar"),
            AssociationField::new('article_ID',"l'article"),
            IntegerField::new('note')
            
        ];
    }
    
}
