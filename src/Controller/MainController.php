<?php

namespace App\Controller;

use App\Entity\Header;
use App\Entity\Product;
use App\Service\MailJet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $bestProducts = $em->getRepository(Product::class)->findByIsBest(1);
        $headers = $em->getRepository(Header::class)->findAll();
      
        return $this->render('main/index.html.twig', [
            'products' => $bestProducts,
            "headers" => $headers
        ]);
    }

}
