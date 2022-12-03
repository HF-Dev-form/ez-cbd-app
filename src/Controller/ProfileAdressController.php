<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Service\Cart\Cart;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileAdressController extends AbstractController
{
    /**
     * @Route("/profile/mes-adresses", name="app_profile_address")
     */
    public function index(AdressRepository $adressRepository): Response
    {
        $addresses = $adressRepository->findAll();
        return $this->render('profile/address.html.twig', [
            'addresses' => $addresses
        ]);
    }

    /**
     * @Route("/profile/ajouter-adresse", name="app_profile_address_add")
     */
    public function addAdress(Request $request, EntityManagerInterface $em, Cart $cart): Response
    {
        $adress = new Adress();
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $adress->setUser($this->getUser());
            $em->persist($adress);
            $em->flush();
            if($cart->getTotal() > 0){
                return $this->redirectToRoute('app_orders');
            }
            return $this->redirectToRoute('app_profile_address');
        }
        return $this->render('profile/address_form.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/profile/modfier-adresse/{id}", name="app_profile_address_update")
     */
    public function updateAdress($id, Request $request, EntityManagerInterface $em): Response
    {
        $adress = $em->getRepository(Adress::class)->find($id);
        if(!$adress || $adress->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_profile_address');
        }
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('app_profile_address');
        }
        return $this->render('profile/address_form.html.twig', [
            "form" => $form->createView(),
        ]);
    }


    /**
     * @Route("/profile/supprimer-adresse/{id}", name="app_profile_address_remove")
     */
     public function removeAdress($id, EntityManagerInterface $em): Response
     {
        $address = $em->getRepository(Adress::class)->find($id);

        if($address && $address->getUser() == $this->getUser()){
            $em->remove($address);
            $em->flush();
            return $this->redirectToRoute('app_profile_address');
        }

     }
}
