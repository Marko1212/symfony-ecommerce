<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{category}", name="category_show")
     */
     public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'products' => $category->getProduct(),
        ]);
    }
}
