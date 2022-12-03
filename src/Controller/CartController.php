<?php

namespace App\Controller;

use App\Service\Cart\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="app_cart")
     */
    public function index(Cart $cart): Response
    {
        
    
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->show(),
            'total' => $cart->getTotal(),
        ]);
    }


    /**
     * @Route("/panier/ajout-panier/{id}", name="app_cart_add", requirements={"id" = "\d+"})
     */
    public function cartAdd($id, Cart $cart): Response
    {

        $cart->add($id);
        return $this->redirectToRoute('app_cart');
    }

      /**
     * @Route("/panier/supprimer-produit/{id}", name="app_cart_remove_item")
     */
    public function deleteOneProductInCart($id, ProductRepository $productRepo, Cart $cart): Response
    {
        $product = $productRepo->find($id);

        if (!$product) {
            $this->addFlash('warning', "Ce produit n'existe pas");
            return $this->redirectToRoute("app_cart");
        }

        $cart->removeItem($id);


       
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/panier/decrementer-produit/{id}", name="app_cart_decrement")
     */
    public function cartProductDecrement($id, Cart $cart): Response
    {
        $cart->decrement($id);
        return $this->redirectToRoute('app_cart');
    }    



    /**
     * @Route("/panier/vider-panier", name="app_cart_remove")
     */
    public function cartRemove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_product');
    }    
    
}
