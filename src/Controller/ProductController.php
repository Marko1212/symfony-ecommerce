<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    

    /**
     * @Route("/products", name="product_list")
     */
    public function index(): Response
    {

        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);

    }

    /**
     * @Route("/product/{slug}_{id}", name="product_show", requirements={"slug"="[a-z0-9\-]*"})
     */
    public function show(Product $product)
    {
        $countReview = count($product->getReviewsList());
        $mark = 0;
        for($i = 0; $i < $countReview; $i++){
            $mark += $product->getReviewsList()[$i]->getMark();
        }
        $mark = $mark / $countReview;
     

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'countReview' => $countReview,
            'mark' => $mark,
        ]);
    }
    
}
