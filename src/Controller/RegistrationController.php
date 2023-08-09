<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatars;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use App\Security\SecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, ManagerRegistry $manager, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, SecurityAuthenticator $authenticator, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // code perso ////////////////////////
            // j'ajoute la date ("created" dans mon entité) à l'inscription
            // $user->setCreated(new \DateTime('now')); 
            // $user->setRoles(["ROLE_ADMIN","ROLE_EDITOR"]);
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();

            // je cree mon avatar après le flush pour recupérer l'id de l'utilisateur
            $avatar = new Avatars();
            
            //je recupere mon user avec son id précédemment créé
            $userID = $manager->getRepository(User::class)->find($user);
            $avatar->setUserID($userID);
            $avatar->setPseudo($user->getName());
            $avatar->setIsVerified(false);

            // je persiste, j'envoie'
            $entityManager->persist($avatar);
            $entityManager->flush();

            // code /////////////////////////////


            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('rahulax.meditation@gmail.com', 'bienvenue sur Rahulax'))
                    ->to($user->getEmail())
                    ->subject('bienvenue sur Rahulax, veuillez confirmer votre mail svp :)')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email


            $mail = (new Email())
                ->from('rahulax.meditation@gmail.com')
                ->to('rahulax.meditation@gmail.com')
                ->subject('Nouvel utilisateur Rahulax/sofrolab '.$user->getName())
                ->html('<p>Nouvel utilisateur enregistré : <b> '.$user->getName().' </b> </p>', )
            ;

            $mailer->send($mail);
           // dd($mailer, $mail);

           $this->addFlash('success', 'Un mail vient de vous être envoyé pour confirmation');

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        
        $avatar = $entityManager->getRepository(Avatars::class)->find($this->getUser()->getAvatar()->getId());


        $avatar->setIsVerified(true);

        $entityManager->persist($avatar);
        $entityManager->flush();

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre mail a bien été vérifié. veuillez vous connecter');

        return $this->redirectToRoute('app_login');
    }
}
