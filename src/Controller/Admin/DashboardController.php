<?php

namespace App\Controller\Admin;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Notes;
use App\Entity\Avatars;
use App\Entity\Friends;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Messages;
use App\Entity\InfosAvatar;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        if(!$this->isGranted("ROLE_ADMIN"))
                {
                    $this->addFlash('infos',"vous n'avez pas les droit");
                    return $this->redirectToRoute('articles');
                }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        return $this->render('admin/dashboard.html.twig');
        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Projet Sofrolab');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('menu Admin');
        yield MenuItem::linkToCrud('Articles', 'fa fa-house-user', Articles::class); 
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-house-user', Comments::class);     
        yield MenuItem::linkToCrud('Messages entre amis', 'fa fa-house-user', Messages::class);  
        yield MenuItem::linkToCrud('Notes d\'articles', 'fa fa-house-user', Notes::class);
        yield MenuItem::linkToCrud('Amitiés', 'fa fa-house-user', Friends::class); 
        yield MenuItem::linkToCrud('infos avatar', 'fa fa-house-user', InfosAvatar::class); 
        yield MenuItem::linkToCrud('infos du site', 'fa fa-house-user', Site::class); 


        if($this->getUser() && $this->isGranted('ROLE_SUPER_ADMIN') ){
            yield MenuItem::section('menu super Admin');

            yield MenuItem::linkToCrud('Avatar', 'fa fa-house-user', Avatars::class); 
            yield MenuItem::linkToCrud('Utilisateur', 'fa fa-house-user', User::class);         
        } 

        
    }
}
