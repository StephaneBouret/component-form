<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\ORM\Events;
use App\Repository\TagRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Product::class)]
class ProductTagListener
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function prePersist(Product $product)
    {
        $tags = $product->getTags();

        foreach ($tags as $key => $tag) {
            $existingTag = $this->tagRepository->findOneBy(['name' => $tag->getName()]);

            if ($existingTag) {
                unset($tags[$key]);
                $product->addTag($existingTag);
            }
        }
    }
}