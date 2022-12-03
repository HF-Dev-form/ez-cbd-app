<?php 

namespace App\Service\Cart;

use App\Service\Cart\ProductsInCart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{   

    private $session;
    private $productRepo;

    public function __construct(SessionInterface $session, ProductRepository $productRepo)
    {
        $this->session = $session;
        $this->productRepo = $productRepo;
    }
    

    public function add($id)
    {
      $cart = $this->session->get('cart', []);

        if(array_key_exists($id, $cart))
        {
            $cart[$id]++;
        }
        else
        {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }


    public function show()
    {
           $cart = [];

        foreach($this->session->get('cart', []) as $id => $qty)
        {
            $product = $this->productRepo->find($id);
            if(!$product)
            {
                continue;
            }
             $cart[] = new ProductsInCart($product, $qty);
        }

        return $cart;
         
    }


    public function remove()
    {
        $this->session->remove('cart');
    }



    public function getTotal()
    {
        $total = 0;

        foreach($this->session->get('cart', []) as $id => $qty)
        {
             $product = $this->productRepo->find($id);
            if(!$product)
            {
                continue;
            }

            $total += ($product->getPrice() * $qty);
        }

        return $total;

    }

     public function removeItem($id)
    {
        $cart = $this->session->get('panier', []);

        unset($cart[$id]);

        $this->session->set('cart', $cart);
    }


     public function decrement($id)
    {
         $cart = $this->session->get('cart', []);

        if(!array_key_exists($id, $cart))
        {
           return;
        }
        if($cart[$id] === 1)
        {
            $this->removeItem($id);
             return;
        }
        else
        {
            $cart[$id]--;

             $this->session->set('cart', $cart);
        }

       
    }
}