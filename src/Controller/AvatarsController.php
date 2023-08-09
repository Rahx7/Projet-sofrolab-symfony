<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Avatars;
use App\Entity\Friends;
use App\Entity\Comments;
use App\Entity\Messages;
use App\Form\Avatars1Type;
use App\Repository\AvatarsRepository;
use App\Repository\FriendsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/avatars')]
class AvatarsController extends AbstractController
{
    private $lastComments;
    private $lastUser;
    private $textSite;
    private $friends;
    private $messages;

   

    public function __construct(ManagerRegistry $manager)
    {
        $this->textSite = $manager->getRepository(Site::class)->find(3);
        $this->lastUser = $manager->getRepository(User::class)->findBy(['isVerified'=> true],['id' => 'desc'], 4);
        $this->lastComments = $manager->getRepository(Comments::class)->findBy(['is_verified'=> true],['id' => 'desc'], 4);
        $this->friends = $manager->getRepository(Friends::class)->findAll();
        $this->messages = $manager->getRepository(Messages::class)->findAll();
    }
    
    #[Route('/', name: 'app_avatars_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $avatars = $entityManager
            ->getRepository(Avatars::class)
            ->findAll();

        $allFriendsUserCertified = $entityManager->getRepository(Friends::class)->findAllFriends();

        return $this->render('avatars/index.html.twig', [
            'avatars' => $avatars,
            "site"=>$this->textSite,
            "lastUser"=>$this->lastUser,
            "lastComments"=>$this->lastComments,
            "friends"=>$this->friends,
            "allFriendsUserCertified"=> $allFriendsUserCertified,
            "messages"=>$this->messages
            
        ]);
    }

    #[Route('/new', name: 'app_avatars_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avatar = new Avatars();
        $form = $this->createForm(Avatars1Type::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $avatar->getPicture();
            //on gerene un nom unique pour chaques images
            $imageName = md5(uniqid()).'.'. $image->guessExtension();

                $image->move(
                    $this->getParameter('upload_files'),
                    $imageName
                );
            $avatar->setPicture($imageName);

            $entityManager->persist($avatar);
            $entityManager->flush();

            return $this->redirectToRoute('app_avatars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avatars/new.html.twig', [
            'avatar' => $avatar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avatars_show', methods: ['GET'])]
    public function show(Avatars $avatar, EntityManagerInterface $entityManager  ): Response
    {
        // for ( ){

        // }
    //    $amis = $this->messages->getAvatarID();


        // dd($this->getUser()->getAvatar(), $this->messages);
        // $this->messages = $manager->getRepository(Messages::class)->findBy(['friends_ID'=>$this->getUser()->getAvatar()->friends]);
        // dump($this->messages);
        
        // $this->messages = $manager->getRepository(Messages::class)->findBy(['friends_ID'=>]);

        // if ($this->getUser() and $this->getUser()->getAvatar()->id == $this->messages){

        // dd($this->getUser()->getAvatar()->id == );
        // $this->messages = $manager->getRepository(Messages::class)->findAll();
        // }

        $friendsUserCertified = $entityManager->getRepository(Friends::class)->findFriends($avatar);
        $friendsUser = $entityManager->getRepository(Friends::class)->findFriendsUser($avatar);




        return $this->render('avatars/show.html.twig', [
            
            'avatar' => $avatar,
            "site"=>$this->textSite,
            "lastUser"=>$this->lastUser,
            "lastComments"=>$this->lastComments,
            "friendsUser"=>$friendsUser,
            "friends"=>$this->friends,
            "friendsUserCertified"=> $friendsUserCertified,
            "messages"=>$this->messages
        ]);
    }

    #[Route('/{id2}/edit', name: 'app_avatars_edit', methods: ['GET', 'POST'], requirements:['id2'=>'\d+'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, $id2): Response
    {
        
        $avatar = $entityManager->getRepository(Avatars::class)->find($id2);

        if(($avatar != $this->getUser()->getAvatar()) && !$this->isGranted('IS_AUTHENTICATED_FULLY')){
            
            $this->addFlash('info',"vous ne pouvez pas modifier l'avatar");
            return $this->redirectToRoute('app_home');

        }

        $form = $this->createForm(Avatars1Type::class, $avatar);
        
        $avatar->setUserID($avatar->getUserID());
        // dd($request->get('user_ID'));
        // $request->get('user_ID') ;
        $form->handleRequest($request);

        //dump($form->isSubmitted());
        if ($form->isSubmitted() && $form->isValid()) {

            
            // dump('test');
            // $user = $entityManager->getRepository(Avatar::class)->find($id2);       
            

            
            $image = $avatar->getPicture();
            //on gerene un nom unique pour chaques images
            $imageName = md5(uniqid()).'.'. $image->guessExtension();
            $image->move(
                    $this->getParameter('upload_files'),
                    $imageName
                );
            $avatar->setPicture($imageName);

            $entityManager->flush();

            $this->addFlash('info','votre avatar a bien été modifié');

            return $this->redirectToRoute('app_avatars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avatars/edit.html.twig', [
            'avatar' => $avatar,
            'form' => $form,
            "site"=>$this->textSite,
            "friends"=>$this->friends,
            "messages"=>$this->messages
        ]);
    }

    #[Route('/{id}', name: 'app_avatars_delete', methods: ['POST'])]
    public function delete(Request $request, Avatars $avatar, EntityManagerInterface $entityManager,$id): Response
    {
        $avatar = $entityManager->getRepository(Avatars::class)->find($id);
        
        if(($avatar != $this->getUser()->getAvatar()) && !$this->isGranted('ROLE_ADMIN')){
            
            $this->addFlash('info',"vous ne pouvez pas supprimer l'avatar");
            return $this->redirectToRoute('app_home');

        }

        if ($this->isCsrfTokenValid('delete'.$avatar->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avatar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avatars_index', [], Response::HTTP_SEE_OTHER);
    }
}
