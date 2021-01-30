<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    

    /**
     * @Route("/products", name="product_list")
     */
    public function index(Request $request): Response
    {

        $repository = $this->getDoctrine()->getRepository(Product::class);

        $products =$repository->findAll();
        $lastProduct = end($products);

       // dump($request->get('colors'));
        if (!empty($request->get('colors'))) {

            $products = $repository->findAllWithFilters(
                $request->get('colors')
            );
        }

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
