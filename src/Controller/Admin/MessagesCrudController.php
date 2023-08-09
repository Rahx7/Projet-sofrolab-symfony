<?php

namespace App\Controller\Admin;

use App\Entity\Messages;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MessagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Messages::class;
    }

    /**/
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('message'),
            AssociationField::new('avatar_ID',"l'ami 1, emeteur"),
            AssociationField::new('friends_ID',"l'id de l'amitié'"),
            DateField::new('created', 'date de création'),

        ];
    }
    
}
