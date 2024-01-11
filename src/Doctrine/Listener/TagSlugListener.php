<?php

namespace App\Doctrine\Listener;

use App\Entity\Tag;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Tag::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Tag::class)]
class TagSlugListener
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Tag $entity)
    {
        if (empty($entity->getSlug())) {
            // SluggerInterface
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }

    public function preUpdate(Tag $entity, PreUpdateEventArgs $event)
    {
        if ($event->hasChangedField('name')) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }
}