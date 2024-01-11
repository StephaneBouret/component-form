<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    #[Route('/admin/tag/create', name: 'tag_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $tag = new Tag;
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('tag/create.html.twig', [
            'formView' => $formView
        ]);
    }

    #[Route('/admin/tag/{id}/edit', name: 'tag_edit')]
    public function edit($id, TagRepository $tagRepository, Request $request, EntityManagerInterface $em)
    {
        $tag = $tagRepository->find($id);

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'formView' => $formView
        ]);
    }
}
