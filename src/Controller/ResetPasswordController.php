<?php

namespace App\Controller;

use DateTime;
use App\Service\MailJet;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reinitialisation-mot-de-passe", name="app_reset_password")
     */
    public function index(Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {   
        
        if($this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        if($request->get('email')){
            $user = $userRepository->findOneByEmail($request->get('email'));
            if($user){
                $reset_password = new ResetPassword();
                $reset_password->setUser($user)
                               ->setToken(uniqid())
                               ->setCreatedAt(new DateTime())
                               ; 
                $em->persist($reset_password);
                $em->flush();

                $mail = new MailJet();
                $url = $this->generateUrl("app_update_password", [
                    'token' => $reset_password->getToken()
                ]);    

                $content = "<p>Bonjour ". $user->getFirstName(). ", <br><br>";
                $content .= "Vous avez demandé à réinitialiser votre mot de passe sur le site EZ-CBD. <br>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant afin de <a href='$url'> modifier votre mot de passe</a>. </p> <br>";
                $content .= "<small>Attention, ce lien ne sera valide que 10 minutes après réception de ce courrier.</small>";
                $mail->send($user->getEmail(), ucfirst($user->getFirstname()).' '.strtoupper($user->getLastname()), "EZ-CBD - Lien de réinitialisation de votre mot de passe", $content);

                $this->addFlash('success', "Un email contenant la procédure à suivre, viens de vous être envoyé. En cas de non réception de l'email de réinitialisation, pensez à vérifier vos spams, ou bien merci de réessayer.");
                return $this->redirectToRoute('app_login');
               
            }else{
                $this->addFlash('warning', "L'email renseigné est invalide, veuillez réessayer.");
                return $this->redirectToRoute('app_reset_password'); 
            }
        }


        return $this->render('reset_password/index.html.twig', [
          
        ]);
    }


    /**
     * @Route("/modification-mot-de-passe/{token}", name="app_update_password")
     */
     public function passwordUpdate($token, EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $hasher)
     {

        $reset_password = $em->getRepository(ResetPassword::class)->findOneByToken($token);
        
        if(!$reset_password){
            return $this->redirectToRoute('app_reset_password');
        }

       $timeleft = $reset_password->getCreatedAt()->modify('+ 10 minutes');
       $now = new DateTime();
       if($now > $timeleft){
           $this->addFlash('warning', "Le lien de réinitialisation a expiré, en fait le lien est valable 10 minutes après la demande");
           return $this->redirectToRoute('app_reset_password');     
       }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $new_password = $form->get('new_password')->getData();
            $user = $reset_password->getUser();
            $newHashedPassword = $hasher->hashPassword($user, $new_password);
            $user->setPassword($newHashedPassword);

            $em->flush();

            $this->addFlash('success', "Votre mot de passe a bien été mis à jour, vous pouvez à nouveau vous connecter");
            return $this->redirectToRoute('app_login');
            
        }
    
        return $this->render('reset_password/update.html.twig', [
                'form' => $form->createView()
        ]);
     }
}
