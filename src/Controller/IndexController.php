<?php

namespace App\Controller;

use App\Entity\Product;
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
        $aleaProducts = $repository->findAleasProducts(
            $request->get('url_image'));


        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'aleaProducts' => $aleaProducts,
        ]);
    }
}
