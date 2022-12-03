<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Service\MailJet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $search_email = $em->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$search_email){
                $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
                $user->setRoles(['ROLE_USER']);
                $em->persist($user);
                $em->flush();

                $email = new MailJet();
                $content = "Bonjour ". $user->getFirstName().",<br> <p>Bienvenue chez Ez-CBD, vous pouvez dès à présent commander tous les produits de notre boutique</p>";
                $email->send($user->getEmail(),$user->getFirstName(), " Bienvenue chez Ez-CBD", $content);

                $this->addFlash("success", "Félicitation". $user->getFirstName(). ", votre compte est créer, vous pouvez dès à présent vous connecter");
                return $this->redirectToRoute('app_login');
            }else{
                $this->addFlash("danger", "L'email que vous avez renseigné existe déjà.");
                $this->redirectToRoute('app_register');          }
           
        }

        return $this->render('register/index.html.twig', [
           'form' => $form->createView(),
           'notification' => $notification
        ]);
    }
}
