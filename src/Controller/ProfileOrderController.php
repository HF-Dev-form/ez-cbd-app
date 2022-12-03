<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileOrderController extends AbstractController
{
    /**
     * @Route("/profile/mes-commandes", name="app_profile_order")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBySuccessOrders($this->getUser());


        return $this->render('profile/order.html.twig', [
           'orders' => $orders
        ]);
    }

    /**
    * @Route("/profile/ma-commande/{reference}", name="app_profile_order_show")
    */
    public function show($reference, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneByReference($reference);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_profile_order');
        }

        return $this->render('profile/order_show.html.twig', [
        'order' => $order
        ]);
    }    
}
