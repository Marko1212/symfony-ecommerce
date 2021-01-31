<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
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

        if ($countReview !== 0) {
            for ($i = 0; $i < $countReview; $i++) {
                $mark += $product->getReviewsList()[$i]->getMark();
            }
            $mark = $mark / $countReview;
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'countReview' => $countReview,
            'mark' => $mark,
        ]);
    }

    /**
     * @Route("/admin/product/new", name="product_create")
     */
    public function create(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        // Lier le formulaire à la requête (pour récupérer $_POST)
        $form->handleRequest($request);

        // On doit vérifier que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            //dump($product);

            $image = $form->get('url_image')->getData();
            if (trim($image) !== '') {
                $product->setUrlImage($image);
            } else {
                $product->setUrlImage('default.jfif');
            }

            // On ajoute le produit dans la base...
            $entityManager = $this->getDoctrine()->getManager();

            //Je mets l'objet en attente
            $entityManager->persist($product);

            // Exécuter la requête
            $entityManager->flush();

            //Redirection et afficher message de succès
            $this->addFlash('success', 'Votre produit ' . $product->getId() . ' a bien été ajouté');
            return $this->redirectToRoute('product_list');
        }


        return $this->render('product/create.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }

    
}
