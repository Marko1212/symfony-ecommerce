<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{option}", name="category_show")
     */
     public function show(ProductRepository $repositoryProduct, $option, CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $option]);

        $products = $repositoryProduct->findBy([ 'category' => $category->getId()]);

        $repository = $this->getDoctrine()->getRepository(Product::class);
        $allProduct = $repository->findAll();
        $lastProduct = end($allProduct);

        $paginatorCat = $paginator->paginate(
            $products,
              $request->query->getInt('page', 1),
              6
          );

        if (!empty($request->get('colors'))) {

            $products = $repository->findAllPerCategoryWithFilters(
                $request->get('colors'),
                $category->getId()
            );
            $paginatorCat = $paginator->paginate(
                $products,
                  $request->query->getInt('page', 1),
                  6
              );
        }

        $categories = $categoryRepository->findAll();
      

        return $this->render('category/show.html.twig', [
            'products' => $products,
            'lastProduct' => $lastProduct,
            'categories' => $categories,
            'paginatorCat' => $paginatorCat,
        ]);
    }

    /**
     * @Route("/creer_une_categorie", name="category_create")
     */
    public function create(Request $request, SluggerInterface $categorySlug): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $option = $categorySlug->slug($category->getName())->lower(); //Le nom de l'annonce devient le-nom-de-l-annonce
            $category->setName($option);

            //Je dois ajouter l'objet dans la BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            // Faire une redirection après l'ajout et afficher un message de succès
            $this->addFlash('success', 'La catégorie a bien été créée');
            return $this->redirectToRoute("index");
            //Afficher le msg flash dans le html

        }

        return $this->render('category/edit.html.twig', [
            //Pour afficher le formulaire
            'categoryForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier_une_categorie/{option}", name="category_edit")
     */
    public function edit(Request $request, CategoryRepository $categoryRepository, $option): Response
    {
        dump($option);
        $category = $categoryRepository->findOneBy(['name' => $option]);

        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La catégorie a bien été modifiée');

            return $this->redirectToRoute('index');
        }

        return $this->render('category/edit.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'category' => $category,
        ]);
    }

    /**
     * @Route("/supprimer_une_categorie/{option}", name="category_delete")
     */
    public function delete(CategoryRepository $categoryRepository, $option)
    {
        $category = $categoryRepository->findOneBy(['name' => $option]);

        //Pour supprimer en base avec Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category); //DELETE FROM
        $entityManager->flush();

        $this->addFlash('danger', 'La catégorie a bien été supprimée');

        return $this->redirectToRoute('index');
    }



}
