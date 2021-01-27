<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_show")
     */
    public function show(): Response
    {
        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/products", name="product_list")
     */
    public function index(): Response
    {
        return $this->render('product/list.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
