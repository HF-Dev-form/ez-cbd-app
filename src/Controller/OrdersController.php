<?php

namespace App\Controller;

use DateTime;
use App\Entity\Order;
use App\Form\OrderType;
use App\Service\Cart\Cart;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrdersController extends AbstractController
{
    /**
     * @Route("/commande", name="app_orders")
     */
    public function index(Cart $cart): Response
    {
         if(!$this->getUser()->getAdresses()->getValues()){
            
            $this->addFlash('info', "Vous n'avez pas encore crée d'adresse");
            return $this->redirectToRoute('app_profile_address_add');
         }   

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);


        return $this->render('orders/index.html.twig', [
            'cart' => $cart->show(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="app_orders_add")
     */
    public function addOrder(Cart $cart, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

         $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $carriers = $form->get('carriers')->getData();    
            $delivery = $form->get('addresses')->getData();

            $fullDelivery ="<p>" . $delivery->getFirstname().' '.$delivery->getLastname();
            $fullDelivery .=  ' <br>'.$delivery->getPhone();  
            if($delivery->getCompany()){
                $fullDelivery .=  ' '.$delivery->getCompany();
            }
            $fullDelivery .=  '<br>'.$delivery->getAdress(); 
            $fullDelivery .=  ' <br>'.$delivery->getPostal().' '.$delivery->getCity();
            $fullDelivery .=  "</p>" ;      

            $order = new Order();
            $dateRef= new DateTime();
            $reference = $dateRef->format('dmY').'-'.uniqid();
            $order
                ->setReference($reference)
                ->setUser($this->getUser())
                ->setCreatedAt(new DateTime())
                ->setCarrierName($carriers->getName())
                ->setCarrierPrice($carriers->getPrice())
                ->setDelivery($fullDelivery)
                ->setState(0)
            ;

            $em->persist($order); 

            foreach($cart->show() as $products){
                $orderDetails = new OrderDetails();
                $orderDetails
                            ->setOrder($order)
                            ->setProduct($products->product->getName())
                            ->setPriceProduct($products->product->getPrice())
                            ->setQuantity($products->qty)
                            ->setTotal($products->product->getPrice()* $products->qty)
                ;
             $em->persist($orderDetails);
            }

            $em->flush();

          
            return $this->render('orders/add.html.twig', [
                'cart' => $cart,
                'carriers' => $carriers,
                'delivery' => $fullDelivery,
                'reference' => $order->getReference()
            ]);

        }
    return $this->redirectToRoute('app_cart');
    
    }
}
