<?php

namespace App\Form;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TagType extends AbstractType
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Solution 1 :
            // ->add('name', TextType::class, [
            //     'label' => 'Nom du tag',
            //     'required' => false,
            // ])
            // Solution 2 :
            ->add('name', ChoiceType::class, [
                'label' => 'Nom du tag',
                'required' => false,
                'choices' => $this->getTagChoices($builder->getData()),
                'placeholder' => null,
                'attr' => [
                    'data-trigger' => '',
                ],
            ]);
    }

    private function getTagChoices(?Tag $tag): array
    {
        $choices = [];

        // Si le tag existe déjà en base de données, utilisez EntityType
        if ($tag && $tag->getId()) {
            $choices = [
                $tag->getName() => $tag->getName(),
            ];
        } else {
            // Sinon, utilisez ChoiceType avec une liste de tags depuis la base de données
            $tags = $this->entityManager->getRepository(Tag::class)->findBy([], ['name' => 'ASC']);

            foreach ($tags as $tag) {
                $choices[$tag->getName()] = $tag->getName();
            }
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
