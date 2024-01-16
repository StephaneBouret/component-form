<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormEvent;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category', priority: -1)]
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{category_slug}/{slug}', name: 'product_show', priority: -1)]
    public function show($slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }

    #[Route('/admin/product/{id}/edit', name: 'product_edit')]
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em)
    {
        $product = $productRepository->find($id);

        // Solution pour éviter les incrémentations en BDD
        // Récupérer les tags existants du produit
        $originalTags = $product->getTags()->toArray();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Solution pour éviter les incrémentations en BDD
            // Comparer les tags existants avec les nouveaux tags sélectionnés
            foreach ($originalTags as $originalTag) {
                if (!$product->getTags()->contains($originalTag)) {
                    // Si un tag existant n'est pas sélectionné, le retirer
                    $product->removeTag($originalTag);
                }
            }

            // Solution pour éviter les incrémentations en BDD
            // Parcourir les nouveaux tags pour vérifier s'ils existent déjà en base de données
            foreach ($product->getTags() as $tag) {
                $existingTag = $em->getRepository(Tag::class)->findOneBy(['name' => $tag->getName()]);

                if ($existingTag) {
                    // Si le tag existe déjà, utilisez le tag existant
                    $product->removeTag($tag); // Supprimer le tag nouvellement ajouté
                    $product->addTag($existingTag); // Ajouter le tag existant
                }
            }

            if ($product->getType() === 'Article') {
                $product->setStatut(null);
            }

            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $form,
        ]);
    }

    #[Route('/admin/product/type-select', name: 'admin_product_type_select')]
    public function getSpecificTypeSelect(Request $request)
    {
        $product = new Product;
        $product->setType($request->query->get('type'));

        $form = $this->createForm(ProductType::class, $product);

        if (!$form->has('statut')) {
            return new JsonResponse(['content' => null, 'success' => true]);
        }

        $html = $this->renderView('partials/_specific_type_name.html.twig', [
            'productForm' => $form->createView(),
        ]);

        return new JsonResponse(['content' => $html, 'success' => true]);
    }
}
