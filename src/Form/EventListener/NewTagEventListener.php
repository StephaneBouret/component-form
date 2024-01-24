<?php

namespace App\Form\EventListener;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;

class NewTagEventListener
{
    private $em;
    private $tagRepository;

    public function __construct(EntityManagerInterface $em, TagRepository $tagRepository)
    {
        $this->em = $em;
        $this->tagRepository = $tagRepository;
    }

    public function onPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $product = $form->getData();

        $newTagName = $form->get('newTag')->getData();

        if (!empty($newTagName)) {
            $newTag = $this->tagRepository->findOneBy(['name' => $newTagName]);

            if (!$newTag) {
                $newTag = new Tag();
                $newTag->setName($newTagName);
                $this->em->persist($newTag);
            }

            $product->addTag($newTag);
        }
    }
}