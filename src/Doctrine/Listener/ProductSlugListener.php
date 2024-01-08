<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Product::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Product::class)]
class ProductSlugListener
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Product $entity)
    {
        if (empty($entity->getSlug())) {
            // SluggerInterface
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }

    public function preUpdate(Product $entity, PreUpdateEventArgs $event)
    {
        if ($event->hasChangedField('name')) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }
}