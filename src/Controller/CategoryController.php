<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{option}", name="category_show")
     */
     public function show(ProductRepository $repositoryProduct, $option, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $option]);

        $products = $repositoryProduct->findBy([ 'category' => $category->getId()]);


        return $this->render('category/show.html.twig', [
            'products' => $products,
        ]);
    }
}
