<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Notes;
use App\Entity\Avatars;
use App\Entity\Friends;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Messages;
use App\Form\FriendsType;
use App\Form\CommentsType;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class HomeController extends AbstractController
{

    private $lastUser;
    private $lastComments;
    private $textSite;
    private $friends;
    private $messages;
    

    public function __construct(ManagerRegistry $manager)
    {
        $this->lastUser = $manager->getRepository(User::class)->findBy(['isVerified'=> true],['id' => 'desc'], 5);
        $this->lastComments = $manager->getRepository(Comments::class)->findBy(['is_verified'=> true],['id' => 'desc'], 4);
        $this->textSite = $manager->getRepository(Site::class)->find(1);
        $this->friends = $manager->getRepository(Friends::class)->findAll();
        // dump($this->getUser()->getAvatar()->getFriends()->getId());
        $this->messages = $manager->getRepository(Messages::class)->findAll();

    }


    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $manager) : Response
        {

            $articles = $manager->getRepository(Articles::class)->findBy([],['id' => 'desc']);

            return $this->render('home/index.html.twig', [
                'articles' =>  $articles,
                "lastUser"=>$this->lastUser,
                "lastComments"=>$this->lastComments,
                "site"=>$this->textSite,
                "friends"=>$this->friends,
                "messages"=>$this->messages
            ]);
        }


        #[Route('/friend/add/{id}', name: 'add_friend', requirements:['id'=>'\d+'])]
    public function addFriend( ManagerRegistry $manager, $id) : Response
        {
             //dd('test');
            $friend = new Friends();

            // dd('test');
            $friend->setVerified(false);
            $friend->setAvatarID1($this->getUser()->getAvatar());
            $friend->setAvatarID2($manager->getRepository(Avatars::class)->find($id));

                try { 

                    $om = $manager->getManager();
                    $om->persist($friend);
                    $om->flush();
                
                    $this->addFlash('info', "la demande d'amitié a bien été envoyé");

                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                }

            return $this->redirectToRoute('app_home');

        }

        
     #[Route('/friend/valid/{id2}', name: 'valid_friend', requirements:['id2'=>'\d+'])]
    public function validFriend(ManagerRegistry $manager, $id2, EntityManagerInterface $entityManager) : Response
        {

            $friend = $manager->getRepository(Friends::class)->find($id2);
            $friend->setVerified(true);

            // $form = $this->createForm(FriendsType::class, $friend);
            // $form->handleRequest($request);
  
            $entityManager->flush();              
            $this->addFlash('info', "la demande d'amitié a bien été validé, c'est maintenant votre ami ! :)");

            return $this->redirectToRoute('app_home');

        }

        #[Route('/friend/message/{id3}', name: 'add_message', requirements:['id3'=>'\d+'])]
        public function addMessage(Request $request, ManagerRegistry $manager, $id3) : Response
            {
                
                $message = new Messages();
                $friend = $manager->getRepository(Friends::class)->find($id3);

                $message->setFriendsID($friend);
                $message->setAvatarID($this->getUser()->getAvatar());

                $message->setMessage($request->get('message'));

                $om = $manager->getManager();
                $om->persist($message);
                $om->flush();
            
                $this->addFlash('info', "le message a bien été envoyé");

                return $this->redirectToRoute('app_home');
    
            }
 

        
        #[Route('/{id}', name: 'add_comment', requirements:['id'=>'\d+'])]
        public function addComment(Request $request, ManagerRegistry $manager, $id, Articles $article,MailerInterface $mailer) : Response
            {
                //dd($article);
                $comment = new Comments();


                
                $form = $this->createForm(CommentsType::class, $comment);
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid())
                {

                    if(in_array('ROLE_EDITOR',$this->getUser()->getRoles())){
                        $comment->setIsVerified(true);
                       // dd($this);
                    }

                    $mail = (new Email())
                    ->from('rahulax.meditation@gmail.com')
                    ->to('rahulax.meditation@gmail.com')
                    ->subject('il y a un nouveaux commentaire en attente de validation !')
                    ->html('<p>Ceci est mon message en HTML</p>');          
                    $mailer->send($mail);

                    //dump($comment);
                    $comment->setAvatarID($this->getUser()->getAvatar());
                    $comment->setArticleID($article);

                    $om = $manager->getManager();
                    $om->persist($comment);
                    $om->flush();
                
                    $this->addFlash('info', "le commentaire a bien été ajouté, il sera visible après validation");

                    return $this->redirectToRoute('article_single',['id'=> $id]);
                }

                return $this->redirectToRoute('article_single',['id'=> $id]);

        }

      
}
