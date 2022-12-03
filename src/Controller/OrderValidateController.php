<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\MailJet;
use App\Service\Cart\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderValidateController extends AbstractController
{
    /**
     * @Route("/commande/succes/{stripeSession}", name="app_order_validate")
     */
    public function index($stripeSession, EntityManagerInterface $em, Cart $cart): Response
    {
        $order = $em->getRepository(Order::class)->findOneByStripeSession($stripeSession);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirect('app_main');
        }

        if($order->getState() == 0){
            $cart->remove();    
            $order->setState(1);
            $em->flush();

        
            $email = new MailJet();
            $content = "Bonjour ". $order->getUser()->getFirstName().",<br> <p>Merci pour votre commande Ez-CBD, <hr>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque voluptate nulla ipsa aperiam atque minima deleniti corporis tempora esse explicabo accusamus aliquid est ullam quae neque, facere reiciendis quia quidem.</p>";
            $email->send($order->getUser()->getEmail(),$order->getUser()->getFirstName(), " Votre commande Ez-CBD est bien validée", $content);
        }
       
        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }
}
