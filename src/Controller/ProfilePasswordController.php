<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilePasswordController extends AbstractController
{
    /**
     * @Route("/profile/modfier-mot-de-passe", name="app_profile_password")
     */
    public function index(Request $request, UserPasswordHasherInterface $hasher,  EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        // dd($user);
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $old_pwd = $form->get('old_password')->getData();
           if($hasher->isPasswordValid($user, $old_pwd)){
            $new_pwd = $form->get('new_password')->getData();
            $newHashedPassword = $hasher->hashPassword($user, $new_pwd);
            $user->setPassword($newHashedPassword);

            $em->flush();

            $this->addFlash('success', "Votre mot de passe a bien été mis à jour" );
            return $this->redirectToRoute('app_profile');
           } else {
             $this->addFlash('danger', "Mot de passe incorrect, veuillez resaisir votre ancien mot de passe" );
            return $this->redirectToRoute('app_profile_password');
           } 
        }
        return $this->render('profile/password.html.twig', [
           'form' => $form->createView()
        ]);
    }
}
