<?php

namespace App\Controller\Admin;

use App\Entity\Friends;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FriendsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Friends::class;
    }

    /**/
    public function configureFields(string $pageName): iterable
    {
        return [

           // IdField::new('id'),
            AssociationField::new('avatar_ID1',"l'ami 1, emeteur"),
            AssociationField::new('avatar_ID2',"l'ami 2"),
            BooleanField::new('verified'),
            DateField::new('created', 'date de création'),
            AssociationField::new('messages',"les messages"),
        ];
    }
    
}
