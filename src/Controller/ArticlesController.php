<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Friends;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Messages;
use App\Form\ArticlesType;
use App\Form\CommentsType;
use App\Repository\ArticlesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ArticlesController extends AbstractController
{
    private $lastComments;
    private $lastUser;
    private $textSite;
    private $friends;
    private $messages;
 



    public function __construct(ManagerRegistry $manager)
    {
        $this->lastUser = $manager->getRepository(User::class)->findBy(['isVerified'=> true],['id' => 'desc'], 5);
        $this->lastComments = $manager->getRepository(Comments::class)->findBy(['is_verified'=> true],['id' => 'desc'], 4);
        $this->textSite = $manager->getRepository(Site::class)->find(2);
        $this->friends = $manager->getRepository(Friends::class)->findAll();
            // dump($this->getUser()->getAvatar()->getFriends()->getId());
        $this->messages = $manager->getRepository(Messages::class)->findAll();
    }

    #[Route('/articles', name: 'articles')]
    public function index(ManagerRegistry $manager): Response
    {     

        $articles = $manager->getRepository(Articles::class)->findAll();
        return $this->render('articles/index.html.twig', [
           "articles"=>$articles, 
           "lastUser"=>$this->lastUser,
           "lastComments"=>$this->lastComments,
           "site"=>$this->textSite,
           "friends"=>$this->friends,
           "messages"=>$this->messages
        ]);
    }

    // #[Route('/articles/{search}', name: 'articles_search')]
    // public function indexSearch(ManagerRegistry $manager, $search): Response
    // {     
        
    //     $articles = $manager->getRepository(Articles::class)->findSearch($search);
    //     return $this->render('articles/index.html.twig', [
    //        "articles"=>$articles, 
    //        "lastUser"=>$this->lastUser,
    //        "lastComments"=>$this->lastComments,
    //        "site"=>$this->textSite,
    //        "friends"=>$this->friends,
    //        "messages"=>$this->messages
    //     ]);
    // }



    #[Route('/articles/{id}', name: 'article_single', requirements:['id'=>'\d+'])]
    public function single(int $id, ArticlesRepository $repository, ManagerRegistry $manager, Request $request ): Response
    {     

        $comments = new Comments;
        $form = $this->createForm(CommentsType::class, $comments);

        $article = $repository->find($id);
        
        return $this->render('articles/single.html.twig', [
           "article"=> $article,
           "lastUser"=>$this->lastUser,
           "lastComments"=>$this->lastComments,
           "site"=>$this->textSite,
           "comments"=>$manager->getRepository(Comments::class)->findBy(['article_ID' => $article, 'is_verified'=> true ],['id' => 'desc']),
           'form'=>$form->createView(),
           "friends"=>$this->friends,
           "messages"=>$this->messages

        ]);
    }



    
    #[
        Route('/articles/save', name: 'articles_save', methods:["POST","GET"]),
    ]
        public function add(ManagerRegistry $manager, Request $request,):response 
            {

                if(!$this->isGranted("ROLE_EDITOR"))
                {
                    $this->addFlash('infos',"vous n'avez pas les droit d'éditeur");
                    return $this->redirectToRoute('articles');
                }

                $article = new Articles;

                $form = $this->createForm(ArticlesType::class, $article);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid())
                {

                    try { 
                            $image = $article->getImg();
                            //on gerene un nom unique pour chaques images
                            $imageName = md5(uniqid()).'.'. $image->guessExtension();

                                $image->move(
                                    $this->getParameter('upload_files'),
                                    $imageName
                                );
                            $article->setImg($imageName);

                            if(!$this->isGranted("ROLE_EDITOR")){
                                $article->setIsVerified(true);
                            } else {
                                $article->setIsVerified(false);
                            }
                            /// on gère l'audio pour renommer et déplacer dans upload
                            $audio = $article->getSrc();
                            //on gerene un nom unique pour chaques images
                            $audioName = md5(uniqid()).'.'. $audio->guessExtension();

                            $audio->move(
                                $this->getParameter('upload_files'),
                                $audioName
                            );

                            $article->setSrc($audioName);
                            // dd($this->getUser()->getAvatar());
                            $article->setAvatarID($this->getUser()->getAvatar());

                        // on persiste pour la base de donnée
                        $om = $manager->getManager();
                        $om->persist($article);
                        $om->flush();
                    
                        $this->addFlash('info', "l'article a bien ete enregistré");

                            return $this->redirectToRoute('article_single', [
                                'id'=>$article->getId(),
                                'article'=> $manager->getRepository(Articles::class)->find($article->getId()),

                            ]);
                    
                    } catch (\Exception $e) {
                        $this->addFlash('error', $e->getMessage());
                    }
                
                }
                    // dump($request);

                return $this->render('articles/save.html.twig',
                [
                    'form' => $form->createView(),
                    "lastUser"=>$this->lastUser,
                    "lastComments"=>$this->lastComments,
                    "friends"=>$this->friends,
                    "messages"=>$this->messages

                ]);
            }


            #[Route('/articles/{id}/delete', name:'articles_delete',requirements:['id'=>'\d+'])]
            public function delete(Articles $article, ManagerRegistry $manager, Request $request){

                if(!$this->isGranted("ROLE_EDITOR"))
                {
                    $this->addFlash('infos',"vous n'avez pas les droit d'éditeur");
                    return $this->redirectToRoute('articles');
                }

                if($article->getAvatarID() != $this->getUser()->getAvatar()){
            
                    $this->addFlash('info',"vous ne pouvez pas supprimer l'article");
                    return $this->redirectToRoute('articles');
        
                }

                $em = $manager->getManager();
                $em->remove($article);
                $em->flush();
                
                $this->addFlash('info','votre article a bien été supprimé');
                return $this->redirectToRoute('app_home');
                

            }

            #[Route('/articles/{id}/update', name:"articles_update")]
            public function update(Articles $article, ManagerRegistry $manager, Request $request){
                $om = $manager->getManager();

                if(!$this->isGranted("ROLE_EDITOR"))
                {
                    $this->addFlash('infos',"vous n'avez pas les droit d'éditeur");
                    return $this->redirectToRoute('articles');
                }

                if($article->getAvatarID() != $this->getUser()->getAvatar()){
            
                    $this->addFlash('info',"vous ne pouvez pas modifier l'article");
                    return $this->redirectToRoute('articles');
        
                }


                if($article->getImg()){
                    $article->setImg(
                        new File($this->getParameter('upload_files').'/'.$article->getImg())
                    );
                }
                if($article->getSrc()){
                    $article->setSrc(
                        new File($this->getParameter('upload_files').'/'.$article->getSrc())
                    );
                }

                $form = $this->createForm(ArticlesType::class, $article);
                $form->handleRequest($request);


                if($form->isSubmitted()&&$form->isValid()){
                    

                        //on récupère l'image ///////////////////////////////////////////////////
                        $image = $article->getImg();
                        //on gerene un nom unique pour chaques images
                        $imageName = md5(uniqid()).'.'. $image->guessExtension();


                        try{
                            $image->move(
                                $this->getParameter('upload_files'),
                                $imageName
                            );
                            $article->setImg($imageName);

                        } catch (FileException $e) {
                            $this->addFlash('error', $e->getMessage()); }


                        //on envoie l'audio /////////////////////////////////////////////////////////
                        $audio = $article->getSrc();
                        //on gerene un nom unique pour chaques images
                        $audioName = md5(uniqid()).'.'. $audio->guessExtension();

                        try{

                            $audio->move(
                                $this->getParameter('upload_files'),
                                $audioName
                            );
                            $article->setSrc($audioName);

                        } catch (FileException $e) {
                            $this->addFlash('error', $e->getMessage()); }
                   
                    
                    /////////////////////////////////////////////////////////////////////

                    
                    $om->persist($article);
                    $om->flush();

                    $this->addFlash('info','votre article a bien été modifié');

                    return $this->redirectToRoute("article_single", [
                        'id'=>$article->getId(),
                        // "lastUser"=>$this->lastUser,
                        // "lastComments"=>$this->lastComments,
                    ]);

                }


                return $this->render("articles/update.html.twig", [

                    'form'=>$form->createView(),
                    'article'=> $article,
                    "lastUser"=>$this->lastUser,
                    "lastComments"=>$this->lastComments,
                    "friends"=>$this->friends,
                    "messages"=>$this->messages
    
                ]);

            }

}


    
