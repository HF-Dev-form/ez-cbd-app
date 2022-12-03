<?php

namespace App\Controller;

use App\Service\Search;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use App\Service\ProductPagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/nos-produits", name="app_product")
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
    
        return $this->render('product/index.html.twig', [
            "products" => $products,
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/produit/{slug}/{id}", name="app_product_show", requirements={"id" = "\d+"})
     */
    public function show($slug, $id, ProductRepository $productRepo): Response
    {
        $product = $productRepo->findOneBy(['slug' => $slug, 'id' => $id]);
        if(!$product){
            $this->addFlash('warning', "Aucun produit trouvé");
            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/show.html.twig', [
            "product" => $product,
            "products" => $productRepo->findByIsBest(1)
        ]);
    }


  
}
