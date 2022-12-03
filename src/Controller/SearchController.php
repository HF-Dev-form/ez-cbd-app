<?php

namespace App\Controller;

use App\Service\Search;
use App\Form\SearchType;
use App\Service\ProductPagination;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche-produits", name="app_search_product")
     */
    public function index(ProductRepository $productRepo, ProductPagination $productPagination, Request $request): Response
    {
        
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){   
            $products = $productPagination->getPaginatedForSearch($search);
        }else{
            $products = $productPagination->getPaginatedProducts($search);
        }
    
        return $this->render('incs/_nav.html.twig', [
            "products" => $products,
            "form" => $form->createView()
        ]);
    }
}
