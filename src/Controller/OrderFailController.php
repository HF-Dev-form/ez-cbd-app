<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderFailController extends AbstractController
{
    /**
     * @Route("/commande/erreur/{stripeSession}", name="app_order_fail")
     */
    public function index($stripeSession, EntityManagerInterface $em): Response
    {
        $order = $em->getRepository(Order::class)->findOneByStripeSession($stripeSession);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirect('app_main');
        }
        
        return $this->render('order_fail/index.html.twig', [
            'order' => $order
        ]);
    }
}
