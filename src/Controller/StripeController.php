<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Service\Cart\Cart;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class StripeController extends AbstractController
{

   protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/commande/create-session/{ref}", name="app_stripe_create_session")
     */
    public function index($ref, Cart $cart, EntityManagerInterface $em): Response
    {
        $products_stripe = [];
        $request = $this->requestStack->getCurrentRequest();
        $DOMAIN =  $request->getSchemeAndHttpHost();;

        $order = $em->getRepository(Order::class)->findByReference($ref);

        // dd($order);

        if(!$order){
             return $this->generateUrl('app_order_fail', [], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        

        header('Content-Type: application/json');
        foreach($cart->show() as $products){

            $products_stripe[] = [
                        'quantity' => $products->qty,
                        'price_data' => [
                            'currency' => 'eur',
                            'unit_amount' => $products->product->getPrice(),
                            'product_data' => [
                                'name' => $products->product->getName(), 
                                'images' => [$DOMAIN."/uploads/".$products->product->getIllustration()],  
                            ]
                        ]                  
            ];     
        }

         $products_stripe[] = [
                        'quantity' => 1,
                        'price_data' => [
                            'currency' => 'eur',
                            'unit_amount' => $order[0]->getCarrierPrice(),
                            'product_data' => [
                                'name' => $order[0]->getCarrierName(), 
                                'images' => [$DOMAIN],  
                            ]
                        ]                  
            ];   

           

        Stripe::setApiKey('sk_test_51KtvjeIXHg8874bq8Te1VqcDs7W7YWlctQeFsJ33VemlRbEqh9lAL0Cm4tpSz6P8GqNMJ2UJZyAxnc8bZc3qJw6L00872pY4to');

            $checkout_session = Session::create([
                'customer_email' => trim($this->getUser()->getEmail()),
                'payment_method_types' => ['card'],
                'line_items' => [$products_stripe],
                'mode' => 'payment',
                'success_url' => $DOMAIN . '/commande/succes/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);
                
            $order[0]->setStripeSession($checkout_session->id);
            $em->flush();
            
      return $this->redirect("$checkout_session->url", 302);
    }
}