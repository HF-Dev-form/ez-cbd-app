<?php

namespace App\Service;

use App\Service\Search;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductPagination {

    private $requestStack;
    private $productRepository;
    private $paginator;
    
    public function __construct(RequestStack $requestStack , ProductRepository $productRepository, PaginatorInterface $paginator)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
        $this->paginator = $paginator; 
    }


    public function getPaginatedProducts(Search $search)
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 8;
        $productQuery = $this->productRepository->findForPagination($search);

        return $this->paginator->paginate($productQuery, $page, $limit);
    }

    public function getPaginatedForSearch(Search $search)
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 8;
        $productQuery = $this->productRepository->findBysearch($search);
        return $this->paginator->paginate($productQuery, $page, $limit);
    }
}