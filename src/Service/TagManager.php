<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\TagRepository;

class TagManager 
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function updateProductTags(Product $product)
    {
        // Solution pour éviter les incrémentations en BDD
        // Récupérer les tags existants du produit
        $originalTags = $product->getTags()->toArray();

        // Mettre à jour les tags existants
        foreach ($originalTags as $originalTag) {
            if (!$product->getTags()->contains($originalTag)) {
                // Si un tag existant n'est pas sélectionné, le retirer
                $product->removeTag($originalTag);
            }
        }

        // Parcourir les nouveaux tags pour vérifier s'ils existent déjà en base de données
        foreach ($product->getTags() as $tag) {
            $existingTag = $this->tagRepository->findOneBy(['name' => $tag->getName()]);

            if ($existingTag) {
                $product->removeTag($tag); // Supprimer le tag nouvellement ajouté
                $product->addTag($existingTag);
            }
        }
    }
}