<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Review;
use App\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $lastProduct = end($products);

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepository->findAll();


        return $this->render('product/list.html.twig', [
            'products' => $products,
            'lastProduct' => $lastProduct,
            'categories' => $categories,
        ]);

    }

    /**
     * @Route("/product/{slug}_{id}", name="product_show", requirements={"slug"="[a-z0-9\-]*"})
     */
    public function show(Product $product, Request $request, $id)
    {
        $countReview = count($product->getReviewsList());
        $mark = 0;
        for($i = 0; $i < $countReview; $i++){
            $mark += $product->getReviewsList()[$i]->getMark();
        }
        $mark = $mark / $countReview;

        $review = new Review();
        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);
        
        

     

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'countReview' => $countReview,
            'mark' => $mark,
            'formReview' => $formReview->createView(),
        ]);
    }
    
}
