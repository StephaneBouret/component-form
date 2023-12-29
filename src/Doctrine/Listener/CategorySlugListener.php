<?php

namespace App\Doctrine\Listener;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: 'prePersist', method: 'prePersist', entity: Category::class)]
class CategorySlugListener 
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;    
    }

    public function prePersist(Category $entity)
    {
        if (empty($entity->getSlug())) {
            // SluggerInterface
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }
}