<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="app_category")
     */
    public function index($id, CategoryRepository $categoryRepository): Response
    {
        $productsCat = $categoryRepository->find($id);
        $catName = $productsCat->getName();
        $productsBycat = $productsCat->getProducts();
        return $this->render('category/index.html.twig', [
            'productsBycat' => $productsBycat,
            'catName' => $catName
        ]);
    }
}
