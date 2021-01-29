<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $aleaProducts = $repository->findAleasProducts();

        $aleaCrush = $repository->findOneAleaCrush();

        $lastProducts = $repository->findlastProducts();

        $repoReview = $this->getDoctrine()->getRepository(Review::class);
        $bestProducts = $repoReview->findBestProducts();


        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'aleaProducts' => $aleaProducts,
            'aleaCrush' => $aleaCrush[0],
            'lastProducts' => $lastProducts,
            'bestProducts' => $bestProducts,
        ]);
    }
}
