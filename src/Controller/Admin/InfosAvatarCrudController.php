<?php

namespace App\Controller\Admin;

use App\Entity\InfosAvatar;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InfosAvatarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InfosAvatar::class;
    }

    /**/
    public function configureFields(string $pageName): iterable
    {
        return [

            //IdField::new('id'),
            AssociationField::new('avatar_ID',"l'avatar"),
            TextField::new('key_info'),
            TextEditorField::new('data'),
            
        ];
    }
    
}
