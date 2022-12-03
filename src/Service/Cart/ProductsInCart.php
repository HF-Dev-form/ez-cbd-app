<?php

namespace App\Service\Cart;

use App\Entity\Product;

class ProductsInCart 
{
    public $qty;
    public $product;

    public function __construct(Product $product, $qty)
    {
        $this->qty = $qty;
        $this->product = $product;
    }

     public function getTotal()
    {
        return $this->product->getPrice()*$this->qty;
    }
}