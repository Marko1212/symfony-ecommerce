<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;

use App\Form\ProductType;

use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
//use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

use DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    

    /**
     * @Route("/products", name="product_list")
     */
    public function index(Request $request, PaginatorInterface $paginatorInterface): Response
    {

        $repository = $this->getDoctrine()->getRepository(Product::class);

        $products =$repository->findAll();
        $lastProduct = end($products);

        $paginatorProducts = $paginatorInterface->paginate(
            $products,
            $request->query->getInt('page', 1),
            6
        );


       // dump($request->get('colors'));
        if (!empty($request->get('colors'))) {

            $products = $repository->findAllWithFilters(
                $request->get('colors')
            );
            $paginatorProducts = $paginatorInterface->paginate(
                $products,
                $request->query->getInt('page', 1),
                6
            );
        }

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        /* DEBUT RECHERCHE DE PRODUITS*/
        if (!empty($request->get('filterName'))) {
            $products = $repository->findAllByName(
                $request->get('filterName')
            );
            $paginatorProducts = $paginatorInterface->paginate(
                $products,
                $request->query->getInt('page', 1),
                6
            );
        }

        /* FIN RECHERCHE DE PRODUITS*/
        return $this->render('product/list.html.twig', [
            'products' => $products,
            'lastProduct' => $lastProduct,
            'categories' => $categories,
            'paginatorProducts' => $paginatorProducts,
        ]);

    }

    /**
     * @Route("/product/{slug}_{id}", name="product_show", requirements={"slug"="[a-z0-9\-]*"})
     */
    public function show(Product $product, Request $request, $slug, ReviewRepository $reviewRepository)
    {
        $countReview = count($product->getReviewsList());

        $mark = 0;

        if ($countReview !== 0) {
            for ($i = 0; $i < $countReview; $i++) {
                $mark += $product->getReviewsList()[$i]->getMark();
            }
            $mark = $mark / $countReview;
        }

        $review = new Review();
        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);
    

        $usernames = $reviewRepository->findAllUsernamesOfReviewsPerProduct($product->getId());

        /* Faire le requête pour l'ajout de la review ici */
        /* #DEBUT [REQUEST FOR ADD REVIEW] */
            if($formReview->isSubmitted() && $formReview->isValid()){
                $review->setProduct($product);
                /** @var User $user
                */
                $user = $this->getUser();
                $review->setUser($user);
                $review->setCreationReview(new DateTime('NOW'));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($review);
                $entityManager->flush();

                return $this->redirectToRoute('product_show', ['slug' => $slug, 'id' => $product->getId()]);
            }
        /* #FIN [REQUEST FOR ADD REVIEW] */


        return $this->render('product/show.html.twig', [
            'product' => $product,
            'countReview' => $countReview,
            'mark' => $mark,
            'formReview' => $formReview->createView(),
            'usernames' => $usernames,
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

    /**
     * @Route("/admin/product/edit/{id}", name="product_edit")
     */
    public function edit(Request $request, Product $product)
    {
        //On doit vérifier que l'utilisateur connecté a bien le droit de modifier le produit

/*         if ($this->getUser()!==$product->getOwner()) {
            throw $this->createAccessDeniedException(); // Renvoie une 403
        } */

        $form = $this->createForm(ProductType::class, $product);

        // Faire le traitement du formulaire

        //on écrit les données de la requête dans l'objet $form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // pas besoin de faire de persist...Doctrine va détecter
            // automatiquement qu'il doit faire un UPDATE
            // ATTENTION si on change le slug aux histoires de redirection (sites de e-commerce...)

            $image = $form->get('url_image')->getData();
            if (trim($image) !== '') {
                $product->setUrlImage($image);
            } else {
                $product->setUrlImage('default.jfif');
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le produit a bien été modifié');
            return $this->redirectToRoute('product_list');
        }
        return $this->render('product/edit.html.twig', [
            'productForm' => $form->createView(),
            'product' => $product,
        ]);

    }

    /**
     * @Route("/admin/product/delete/{id}", name="product_delete")
     */
    public function delete(Request $request, Product $product)
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($product);
    
            $entityManager->flush();
    
            $this->addFlash('danger', 'Le produit a bien été supprimé');

        }

        return $this->redirectToRoute('product_list');                      
    }

    
}
